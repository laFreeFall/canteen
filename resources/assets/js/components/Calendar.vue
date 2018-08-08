<template>
    <b-row class="justify-content-center">
        <b-col md="12">
            <b-card>
                <h5 slot="header" class="text-center">Календарь</h5>
                <b-row>
                    <b-col md="4" class="mb-4" v-for="month in calendar" :key="month.id">
                        <b-card :border-variant="month.id === currentMonth.id ? 'primary' : ''"
                            :header-bg-variant="month.id === currentMonth.id ? 'primary' : ''"
                            :header-text-variant="month.id === currentMonth.id ? 'white' : ''"
                            class="full-parent-height"
                            body-class="row align-items-center"
                        >
                            <h6 slot="header" class="text-center">
                                {{ month.title }} {{ month.year.number }}
                            </h6>
                            <table class="table table-bordered table-sm text-center mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th scope="col">
                                            №
                                        </th>

                                        <th scope="col" v-for="dayName in daysNames" :key=dayName.id>
                                            {{ dayName.abbr }}
                                        </th>

                                        <!-- <th>
                                            Отчёт
                                        </th> -->
                                    </tr>
                                </thead>

                                <tbody>
                                    <tr v-for="week in month.weeks" :key="week.id">
                                        <th scope="row">
                                            <router-link
                                                :to="{
                                                    name: 'report',
                                                    params: {
                                                        id: week.id
                                                    }
                                                }"
                                            >
                                                {{ week.id }}
                                            </router-link>
                                        </th>

                                        <td
                                            v-for="day in week.days"
                                            :key=day.id
                                            class="calendar-day"
                                            :class="{ 'bg-light text-muted' : !day.active, 'text-white bg-primary': day.id === currentDay.id }"
                                            v-tooltip="day.active ? `${day.number} ${month.genitive} - учебный день` : `${day.number} ${month.genitive} - выходной день`"
                                            @click="toggleDay(day)"
                                        >
                                            <strong>{{ day.number | withLeadingZero }}.</strong><span class="small" :class="day.id === currentDay.id ? 'text-white' : 'text-muted'">{{ day.month.number | withLeadingZero }}</span>
                                        </td>
                                        
                                        <!-- <td>
                                            <icon name="arrow-alt-circle-right"></icon>
                                        </td> -->
                                    </tr>
                                </tbody>
                            </table>
                        </b-card>
                    </b-col>
                </b-row>
            </b-card>
        </b-col>
    </b-row>
</template>

<script>
    import { mapGetters, mapActions } from 'vuex'

    export default {
        data () {
            return {
                monthsInRow: 3
            }
        },
        methods: {
            ...mapActions([
                'addActiveDay',
                'deleteActiveDay'
            ]),

            toggleDay (day) {
                if (day.active) {
                    this.deleteActiveDay(day)
                        .then(response => {
                            this.$snotify.success('День был отмечен как выходной!', '')
                        })
                        .catch(error => {
                            this.$snotify.error('При попытке отметить день как выходной произошла ошибка.', 'Ошибка')
                        })
                } else {
                    this.addActiveDay(day)
                        .then(response => {
                            this.$snotify.success('День был отмечен как учебный!', '')
                        })
                        .catch(error => {
                            this.$snotify.error('При попытке отметить день как учебный произошла ошибка.', 'Ошибка')
                        })
                }
            }
        },
        computed: {
            ...mapGetters([
                'calendar',
                'daysNames',
                'currentMonth',
                'currentDay'
            ])
        }
    }
</script>

<style>
    .full-parent-height {
        height: 100%;
    }

    .calendar-day {
        cursor: pointer;
        transition: all .3s ease-in-out;
    }

    .calendar-day:hover {
        background-color: #17a2b8 !important;
        color: #fff;
        transition: all .3s ease-in-out;
    }

    .calendar-day:hover span.small {
        color: #fff !important;
        transition: all .3s ease-in-out;
    }
</style>
