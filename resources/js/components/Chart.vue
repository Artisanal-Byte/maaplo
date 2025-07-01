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
        const self = this;
        return {
            chartOptions: {
                chart: {
                    id: 'basic-bar',
                    toolbar: {
                        show: true,
                        tools: {
                            download: true,
                        },
                        export: {
                            csv: {
                                formatter: (series, opts) => {
                                    const labels = self.chartLabels;
                                    const rows = self.chartRows;
                                    let csv = '';

                                    if (self.chartType === 'order') {
                                        csv = 'Date,Order Number,Customer Name,Status\n';
                                        for (let i = 0; i < labels.length; i++) {
                                            const row = rows[i] || {};
                                            csv += `${labels[i]},${row.orderNumber || ''},${row.customerName || ''},${row.status || ''}\n`;
                                        }
                                    } else if (self.chartType === 'customer') {
                                        csv = 'Date,Customer Name,Email,Phone\n';
                                        for (let i = 0; i < rows.length; i++) {
                                            const row = rows[i];
                                            csv += `${row.date},${row.name || ''},${row.email || ''},${row.phone || ''}\n`;
                                        }
                                    } else if (self.chartType === 'revenue') {
                                        csv = 'Date,Revenue\n';
                                        for (let i = 0; i < labels.length; i++) {
                                            csv += `${labels[i]},${self.chartData[i]}\n`;
                                        }
                                    }

                                    return csv;
                                }
                            }
                        }
                    }
                },
                xaxis: {
                    type: 'datetime',
                },
            },
            series: [] // initialized in mounted()
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
                    return 'New Customers';
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
                csv += 'Date,Revenue\n';
                for (let i = 0; i < labels.length; i++) {
                    csv += `${labels[i]},${this.chartData[i]}\n`;
                }
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
    <div class="z-10 example rounded-lg border border-[#167893] mt-8 p-1 lg:p-4 lg:h-[400px] h-[200px]">
        <apexcharts height="100%" type="line" :options="chartOptions" :series="series" />
        <!-- CSV Export Button -->
        <div class="mt-4">
            <button @click="exportCSV" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Export CSV
            </button>
        </div>
    </div>
</template>
