<template>
    <div class="container">
        <h1>Statistiche</h1>
        <div class="ms_container">
            <div class="filters">
                <label for="month_filter">Filtra per mese</label>
                <select id="month_filter" name="month_filter" v-model="month" @change="getAllViews()">
                    <option value="" disabled>Filtra per mese</option>
                    <option value="all">Tutte</option>
                    <option v-for="(month, index) in monthsSelect" :key="index" :value="month.number">{{month.name}}</option>
                </select>
            </div>
            <div class="ms_chart_container">
                <canvas id="views-chart"></canvas>
            </div>
        </div>
    </div>
</template>

<script>
import Chart from 'chart.js';

export default {

    name: 'Chart',

    data() {
        return {
            monthsSelect: [
                {
                    'name': 'Gennaio',
                    'number': '01'
                },
                {
                    'name': 'Febbraio',
                    'number': '02'
                },
                {
                    'name': 'Marzo',
                    'number': '03'
                },
                {
                    'name': 'Aprile',
                    'number': '04'
                },
                {
                    'name': 'Maggio',
                    'number': '05'
                },
                {
                    'name': 'Giugno',
                    'number': '06'
                },
                {
                    'name': 'Luglio',
                    'number': '07'
                },
                {
                    'name': 'Agosto',
                    'number': '08'
                },
                {
                    'name': 'Settembre',
                    'number': '09'
                },
                {
                    'name': 'Ottobre',
                    'number': '10'
                },
                {
                    'name': 'Novembre',
                    'number': '11'
                },
                {
                    'name': 'Dicembre',
                    'number': '12'
                }
            ],
            month: '',
        }
    },

    methods: {
        getAllViews() {
            let viewsData = [];
            let mailData = [];
            console.log(viewsData);
            axios.get(`/admin/statistics/show/${window.Apartment.id}/${this.month}`)
                .then(response => {
                    console.log(response);
                    for (const key in response.data.data.views) {
                        viewsData.push(response.data.data.views[key]);
                    };
                    for (const key in response.data.data.messages) {
                    mailData.push(response.data.data.messages[key]);
                    };
                    console.log(viewsData);
                    const ctx = document.getElementById('views-chart');
                    window.chart.destroy();                        
                    if(this.month == 'all') {
                        let lables = [];
                        this.monthsSelect.forEach(element => {
                            lables.push(element.name);
                        });
                        let data = [];
                        let mail = [];
                        viewsData.forEach(element => {
                            data.push(element.length);
                        });
                        mailData.forEach(element => {
                            mail.push(element.length);
                        });
                        console.log(data);
                        window.chart = new Chart(ctx, {
                        type: "line",
                        data: {
                            labels: lables,
                            datasets: [
                                {
                                    label: 'Visualizzazioni',
                                    data: data,
                                    backgroundColor: "#ffa62880",
                                    borderColor: "#ffa628",
                                    borderWidth: 3
                                },
                                {
                                label: 'Messaggi',
                                data: mail,
                                backgroundColor: "#16697a80",
                                borderColor: "#16697a",
                                borderWidth: 3
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            lineTension: 1,
                            scales: {
                                yAxes: [
                                    {
                                        ticks: {
                                            beginAtZero: true,
                                            padding: 25
                                        }
                                    }
                                ]
                            }
                        }
                        });
                    }else {
                        let label = '';
                        this.monthsSelect.forEach(element => {
                            if (element.number == this.month){
                                label = element.name;
                            }
                        });
                        window.chart = new Chart(ctx, {
                            type: "line",
                            data: {
                                labels: ["1^ Settimana", "2^ Settimana", "3^ Settimana", "4^ Settimana"],
                                datasets: [
                                    {
                                        label: `Visualizzazioni di ${label}`,
                                        data: [viewsData[0].length, viewsData[1].length, viewsData[2].length, viewsData[3].length],
                                        backgroundColor: "#ffa62880",
                                        borderColor: "#ffa628",
                                        borderWidth: 3
                                    },
                                    {
                                        label: `Messaggi di ${label}`,
                                        data: [mailData[0].length, mailData[1].length, mailData[2].length, mailData[3].length],
                                        backgroundColor: "#16697a80",
                                        borderColor: "#16697a",
                                        borderWidth: 3
                                    }
                                ]
                            },
                            options: {
                                responsive: true,
                                lineTension: 1,
                                scales: {
                                    yAxes: [
                                        {
                                            ticks: {
                                                beginAtZero: true,
                                                padding: 25
                                            }
                                        }
                                    ]
                                }
                            }
                        });
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
        }
    },

    mounted() {
        let viewsData = [];
        let mailData = [];
        axios.get(`/admin/statistics/show/${window.Apartment.id}/all`)
            .then(response => {
                console.log(response.data.data.views);
                for (const key in response.data.data.views) {
                    viewsData.push(response.data.data.views[key]);
                };
                for (const key in response.data.data.messages) {
                    mailData.push(response.data.data.messages[key]);
                };
                console.log(mailData);
                const ctx = document.getElementById('views-chart');
                ctx.innerHTML = '';
                let lables = [];
                this.monthsSelect.forEach(element => {
                    lables.push(element.name);
                });
                let data = [];
                let mail = [];
                viewsData.forEach(element => {
                    data.push(element.length);
                });
                mailData.forEach(element => {
                    mail.push(element.length);
                });
                console.log(data);                        
                window.chart = new Chart(ctx, {
                    type: "line",
                    data: {
                        labels: lables,
                        datasets: [
                            {
                                label: 'Visualizzazioni',
                                data: data,
                                backgroundColor: "#ffa62880",
                                borderColor: "#ffa628",
                                borderWidth: 3
                            },
                            {
                                label: 'Messaggi',
                                data: mail,
                                backgroundColor: "#16697a80",
                                borderColor: "#16697a",
                                borderWidth: 3
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        lineTension: 1,
                        scales: {
                            yAxes: [
                                {
                                    ticks: {
                                        beginAtZero: true,
                                        padding: 25
                                    }
                                }
                            ]
                        }
                    }
                });
            })
            .catch(function (error) {
                console.log(error);
            });
    }
}
</script>

<style lang="scss" scoped>
.ms_container{
    display: flex;
    justify-content: space-between;
    max-width: 992px;
    width: 90%;
    margin-block: 2rem;

    .filters {
        display: flex;
        flex-direction: column;
        width: calc(20% - 1rem);
        min-width: 100px;
    }

    .ms_chart_container {
        max-width: 800px;
        width: 80%;
        margin: 0 auto;
        background-color: #ede7e3;
        border-radius: 1rem;
        overflow: hidden;
    }
}
</style>