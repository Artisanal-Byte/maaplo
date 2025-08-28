<script setup>
import { ref } from "vue";
import jsPDF from "jspdf";
import autoTable from "jspdf-autotable";
import { Icon } from "@iconify/vue";

const props = defineProps({
    order: Object,
    show: Boolean,
});
console.log("InvoiceGenerator props:", props.show, props.order);
const emit = defineEmits(["close"]);

function closeModal() {
    emit("close");
}

function formatCurrency(value) {
    const num = parseFloat(value);
    if (isNaN(num)) return "₹ 0.00";
    return `₹ ${num.toLocaleString("en-IN", {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    })}`;
}

async function getBase64ImageFromUrl(imageUrl) {
    const res = await fetch(imageUrl);
    const blob = await res.blob();
    return new Promise((resolve) => {
        const reader = new FileReader();
        reader.onloadend = () => resolve(reader.result);
        reader.readAsDataURL(blob);
    });
}

function formatDate(dateString) {
    if (!dateString) return "-";
    const d = new Date(dateString);
    const day = String(d.getDate()).padStart(2, "0");
    const month = String(d.getMonth() + 1).padStart(2, "0");
    const year = d.getFullYear();
    return `${day}-${month}-${year}`;
}

async function downloadPDF() {
    const doc = new jsPDF("p", "mm", "a4");

    // Sizes
    const pageWidth = doc.internal.pageSize.getWidth();
    const pageHeight = doc.internal.pageSize.getHeight();

    const marginX = 14;
    const headerHeight = 50;
    const footerHeight = 50;

    // ---------------- HEADER ----------------
    let contentY = headerHeight;
    const orgName = props.order?.organization?.organization_name || "Your Organization";
    const orgLogo = props.order?.organization?.organization_logo
        ? `${window.location.origin}/${props.order.organization.organization_logo}`
        : null;

    // Logo (Top-left)
    if (orgLogo) {
        try {
            const base64Logo = await getBase64ImageFromUrl(orgLogo);
            doc.addImage(base64Logo, "PNG", marginX, 10, 30, 30);
        } catch (e) {
            console.warn("Logo load error:", e);
        }
    }

    // Invoice Title (Top-right, aligned with logo vertically)
    // let contentY = headerHeight; // start below logo & title
    const rightX = pageWidth / 2 + 10;
    doc.setFontSize(22);
    doc.setTextColor(0, 0, 0);
    doc.text("INVOICE", rightX, contentY - 20, { align: "left" });

    // ---------------- ORG & BANK DETAILS ----------------
    // ORG (Left)
    doc.setTextColor(0);
    doc.setFontSize(12);
    doc.text("Organization Details", marginX, contentY);

    doc.setFontSize(10);
    // doc.text(orgName, marginX, contentY + 6);
    doc.text("Name: " + orgName, marginX, contentY + 6);
    doc.text("Address: " + (props.order?.organization?.address || "-"), marginX, contentY + 12);
    doc.text(props.order?.organization?.organization_address || "-", marginX, contentY + 12);
    doc.text(`Phone: ${props.order?.organization?.organization_phone || "-"}`, marginX, contentY + 18);

    // BANK (Right)
    doc.setFontSize(12);
    doc.text("Bank Details", rightX, contentY);

    doc.setFontSize(10);
    doc.text("Account Holder: " + (props.order?.organization?.account_holder_name || "-"), rightX, contentY + 6);
    doc.text(`A/C No: ${props.order?.organization?.account_number || "-"}`, rightX, contentY + 12);
    doc.text(`IFSC: ${props.order?.organization?.ifsc_code || "-"}`, rightX, contentY + 18);
    doc.text(`GSTIN: ${props.order?.organization?.gstin || "-"}`, rightX, contentY + 24);
    doc.text("Branch: " + (props.order?.organization?.branch_name || "-"), rightX, contentY + 30);
    doc.text(`Bank: ${props.order?.organization?.bank_name || "-"}`, rightX, contentY + 36);

    // ---------------- CUSTOMER & QR ----------------
    contentY += 50;

    // Customer
    doc.setFontSize(12);
    doc.text("Customer Details", marginX, contentY);

    doc.setFontSize(10);
    doc.text(`Name: ${props.order?.customer?.name || "-"}`, marginX, contentY + 6);
    doc.text(`Phone: ${props.order?.customer?.phone || "-"}`, marginX, contentY + 12);
    doc.text(`Address: ${props.order?.customer?.address || "-"}`, marginX, contentY + 18);

    // QR Code
    doc.setFontSize(12);
    doc.text("QR Code", rightX, contentY);

    if (props.order?.qr_code) {
        try {
            const username = props.order?.user?.username?.toLowerCase().replace(/\s+/g, "_");
            const userId = props.order?.user?.id;

            const basePath = `storage/user_name_${username}_id_${userId}/qr_payment_images`;
            const fullPath = `${window.location.origin}/${basePath}/${props.order.qr_code}`;

            const base64Qr = await getBase64ImageFromUrl(fullPath);
            doc.addImage(base64Qr, "PNG", rightX + 40, contentY - 5, 30, 30);
        } catch (e) {
            console.warn("QR load error:", e);
        }
    }



    // ---------------- TABLE ----------------
    const items = props.order?.order_items || [];

    // Table rows (items)
    const tableData = items.map((item, i) => [
        `Item-${i + 1}`,
        formatCurrency(item?.item_cost) || "-"
    ]);

    // ---- Calculate totals ----
    const total = items.reduce((sum, item) => sum + Number(item?.item_cost || 0), 0);

    const gstRate = 0.18; // 18% GST

    const orgState = props.order?.organization?.state?.toLowerCase();
    const customerState = props.order?.customer?.state?.toLowerCase();

    let cgst = 0, sgst = 0, igst = 0;

    if (orgState && customerState && orgState === customerState) {
        // Intra-state: CGST + SGST (split)
        cgst = total * (gstRate / 2);
        sgst = total * (gstRate / 2);
    } else {
        // Inter-state: IGST
        igst = total * gstRate;
    }

    const tax = cgst + sgst + igst;
    const payable = total + tax;

    // Common border style

    const borderStyle = {

        lineWidth: 0.5,

        lineColor: [0, 0, 0]

    };
    // ---- Append summary rows ----
    tableData.push([
        {
            content: "Total",
            styles: { lineWidth: 0, fontStyle: "bold", halign: "left" }
        },
        {
            content: formatCurrency(total),
            styles: { lineWidth: 0, halign: "left" }
        }
    ]);

    if (cgst || sgst) {
        tableData.push(...[
            [
                { content: "CGST (9%)", styles: { halign: "left" } },
                { content: formatCurrency(cgst), styles: { halign: "left" } }
            ],
            [
                { content: "SGST (9%)", styles: { halign: "left" } },
                { content: formatCurrency(sgst), styles: { halign: "left" } }
            ]
        ]);
    } else {
        tableData.push(...[
            [
                { content: "IGST (18%)", styles: { halign: "left" } },
                { content: formatCurrency(igst), styles: { halign: "left" } }
            ]
        ]);
    }


    tableData.push([
        {
            content: "Total Payable",
            styles: { fontStyle: "bold", halign: "left" }
        },
        {
            content: formatCurrency(payable),
            styles: { fontStyle: "bold", halign: "left" }
        }
    ]);


    autoTable(doc, {
        startY: contentY + 35,
        head: [["Item Name", "Amount"]],
        body: tableData,
        theme: "grid",
        styles: {
            fontSize: 9,
            cellPadding: 3,
            lineWidth: 0.5,
            // lineColor: [0, 0, 0]
        },
        headStyles: {
            fillColor: [0, 0, 0],
            textColor: 255,
            halign: "left",
            fontSize: 10
        },
        bodyStyles: { valign: "middle" },
        columnStyles: {
            0: { valign: "middle", halign: "left" },
            1: { valign: "middle", halign: "left" }
        },
        alternateRowStyles: { fillColor: [245, 245, 245] },
        margin: { left: marginX, right: marginX }
    });

    // ---------------- FOOTER ----------------
    const footerY = pageHeight - footerHeight;
    doc.setDrawColor(200);
    doc.line(marginX, footerY, pageWidth - marginX, footerY);
    doc.setFontSize(9);
    doc.setTextColor(100);
    doc.text("Thank you This is Computer Generated Invoice!", pageWidth / 2, footerY + 7, { align: "center" });

    doc.save(`Bill_${props.order?.order_number || "NA"}.pdf`);
}



</script>


<template>
    <div v-if="show" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl p-8 relative">
            <button class="absolute top-4 right-4 text-gray-600 text-2xl hover:text-red-500 transition"
                @click="closeModal">
                &times;
            </button>

            <h2 class="text-2xl font-extrabold text-primary mb-6 text-center">Bill / Invoice Preview</h2>

            <!-- Customer + Order Info -->
            <div class="grid grid-cols-2 gap-6 bg-gray-50 p-4 rounded-lg border mb-6 text-sm">
                <div>
                    <h3 class="font-semibold text-primary mb-2">Customer</h3>
                    <p><strong>Name:</strong> {{ order?.customer?.name }}</p>
                    <p><strong>Phone:</strong> {{ order?.customer?.phone }}</p>
                    <p><strong>Address:</strong> {{ order?.customer?.address }}</p>
                </div>
                <div>
                    <h3 class="font-semibold text-primary mb-2">Order</h3>
                    <p><strong>No:</strong> {{ order?.order_number }}</p>
                    <p><strong>Status:</strong> {{ order?.status }}</p>
                    <p><strong>Delivery:</strong> {{ formatDate(order?.delivery_date) }}</p>
                    <p><strong>Close Date:</strong> {{ formatDate(order?.close_date) }}</p>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-center gap-6 mt-6">
                <button @click="downloadPDF"
                    class="bg-primary text-white px-6 py-2 rounded-lg shadow flex items-center gap-2 transition">
                    <Icon icon="teenyicons:pdf-outline" width="18" height="18" />
                    Download PDF
                </button>
                <button class="bg-green-500 text-white px-6 py-2 rounded-lg shadow flex items-center gap-2 transition">
                    <Icon icon="bi:whatsapp" width="18" height="18" />
                    Send via WhatsApp
                </button>
            </div>
        </div>
    </div>
</template>
