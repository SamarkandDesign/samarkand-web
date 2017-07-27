import Vue from 'vue'
import Chart from 'chart.js';

export default Vue.extend({
    template: `<div><canvas ref="chart"></canvas></div>`,
    data () {
        return {
            colors: {
                background: [
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                ],
                border: [
                    'rgba(54, 162, 235, 1)',
                    'rgba(255,99,132,1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ]
            }
        }
    },
    methods: {
        render (data) {
            const chart = new Chart(this.$refs.chart, {data: data, type: 'bar'})
        },
        getData () {
            return this.$http.get('/api/data/sales')
        },
        processData (data) {
            const colorCount = this.colors.background.length
            const datasets = Object.keys(data.datasets).map(k => data.datasets[k])

            data.datasets = datasets.map((dataset, index) => {
                return Object.assign({}, dataset, {
                    backgroundColor: this.colors.background[index % colorCount],
                    borderColor: this.colors.border[index % colorCount],
                    borderWidth: 1
                })
            })
            return data
        }
    },
    mounted () {
        this.getData()
            .then(data => this.processData(data.data) )
            .then(this.render)
            .catch(err => console.error(err))
    }
})
