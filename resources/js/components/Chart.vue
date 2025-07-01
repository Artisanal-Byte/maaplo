<script>
import VueApexCharts from 'vue3-apexcharts'

export default {
    name: 'Chart',
    components: {
        apexcharts: VueApexCharts,
    },
    props: {
        chartLabels: {
            type: Array,
            default: () => []
        },
        chartData: {
            type: Array,
            default: () => []
        },
        chartRows: {
            type: Array,
            default: () => []
        },
        chartType: {
            type: String,
            default: 'order' // options: 'order', 'customer', 'revenue'
        }
    },
    data() {
        return {
            chartOptions: {
                chart: {
                    id: 'basic-bar',
                    toolbar: {
                        show: true,
                        tools: {
                            download: true,
                            selection: true,
                            zoom: true,
                            zoomin: true,
                            zoomout: true,
                            pan: true,
                            reset: true,
                        },
                        export: {
                            csv: {
                                enabled: false // ✅ disables ApexCharts CSV option in toolbar
                            },
                            svg: {
                                enabled: true
                            },
                            png: {
                                enabled: true
                            }
                        }
                    }
                },
                xaxis: {
                    type: 'datetime',
                }
            },
            series: []
        }
    },
    mounted() {
        this.updateSeries(this.chartLabels, this.chartData);
    },
    watch: {
        chartType(newType) {
            this.updateSeries(this.chartLabels, this.chartData);
        },
        chartLabels(newLabels) {
            this.updateSeries(newLabels, this.chartData);
        },
        chartData(newData) {
            this.updateSeries(this.chartLabels, newData);
        }
    },
    methods: {

        updateSeries(labels, data) {
            this.series = [{
                name: this.getSeriesName(),
                data: labels.map((label, index) => ({
                    x: new Date(label).toISOString(),
                    y: data[index]
                }))
            }];
        },
        getSeriesName() {
            switch (this.chartType) {
                case 'order':
                    return 'Orders';
                case 'customer':
                    return 'Customers';
                case 'revenue':
                    return 'Revenue';
                default:
                    return 'Data';
            }
        },
        exportCSV() {
            let csv = '';
            const labels = this.chartLabels;
            const rows = this.chartRows;

            if (this.chartType === 'order') {
                csv += 'Date,Order Number,Customer Name,Status\n';
                for (let i = 0; i < labels.length; i++) {
                    const row = rows[i] || {};
                    csv += `${labels[i]},${row.orderNumber || ''},${row.customerName || ''},${row.status || ''}\n`;
                }
            } else if (this.chartType === 'customer') {
                csv += 'Date,Customer Name,Email,Phone\n';
                for (let i = 0; i < rows.length; i++) {
                    const row = rows[i];
                    csv += `"${row.date}","${row.name || ''}","${row.email || ''}","${row.phone || ''}"\n`;
                }
            } else if (this.chartType === 'revenue') {
                csv += 'Date,Revenue,Total Earning,Total Pending\n';

                let totalRevenue = 0;
                let totalEarning = 0;

                for (let i = 0; i < this.chartRows.length; i++) {
                    const row = this.chartRows[i];
                    const revenue = row.revenue || 0;
                    const earning = row.earning || 0;
                    const pending = revenue - earning;

                    totalRevenue += revenue;
                    totalEarning += earning;

                    csv += `${row.date},${revenue},${earning},${pending}\n`;
                }

                const totalPending = totalRevenue - totalEarning;

                // Add final total row
                csv += `Total,${totalRevenue},${totalEarning},${totalPending}\n`;
            }

            const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement("a");
            link.setAttribute("href", URL.createObjectURL(blob));
            link.setAttribute("download", `${this.chartType}_export.csv`);
            link.style.visibility = 'hidden';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    }
}
</script>

<template>
    <!-- Wrapper for button and chart -->
    <div class="relative">

        <!-- Top right export button -->
        <div class="flex justify-end mb-2">
            <button @click="exportCSV" class="bg-primary mt-4 text-white font-bold py-1 px-2 rounded-lg">
                Export CSV
            </button>
        </div>

        <!-- Chart container -->
        <div class="z-10 example rounded-lg border border-[#167893] p-1 lg:p-4 lg:h-[400px] h-[200px]">
            <apexcharts height="100%" type="line" :options="chartOptions" :series="series" />
        </div>
    </div>
</template>
