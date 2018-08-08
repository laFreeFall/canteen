<template>
    <tr>
        <!-- <th scope="row" class="text-left">
            {{ index }}
        </th> -->
        <td class="text-left">
            {{ shortName }}
        </td>
        <td>
            <div class="input-group input-group-sm">
                <input type="number" step="0.01" class="form-control form-control-sm" @blur=pay($event) @keyup.enter=pay($event) aria-label="Сумма (сколько заплатил на этой неделе)">
                <div class="input-group-append">
                    <span class="input-group-text">&#8372;</span>
                </div>
            </div>
        </td>
        <td v-for="day in activeDays" :key="day.id">
            <div class="btn-group" role="group">
                <button
                    class="btn btn-outline-dark btn-sm dish-btn"
                    v-for="(dish, index) in pupilDishes(pupil.id, day.id)" :key="index"
                    v-tooltip="`${dish.title}: ${dish.price / 100}&#8372;`"
                    @click="removeDish(day.id, dish.id)"
                >
                    {{ dish.abbr }}
                </button>
            </div>
            <button
                    class="btn btn-outline-primary btn-sm dish-btn"
                    @click="addDish(day.id)"
                    title="Добавить блюдо"
                    v-b-popover.html="popoverContent"
                >
                    +
            </button>
        </td>

        <td>
            <div class="input-group input-group-sm">
                <input type="number" step="0.01" class="form-control" disabled aria-label="Потратил (сколько потратил на этой неделе)">
                <div class="input-group-append">
                    <span class="input-group-text">&#8372;</span>
                </div>
            </div>
        </td>

        <td>
            <div class="input-group input-group-sm">
                <input type="number" step="0.01" class="form-control" disabled aria-label="Долг (сколько должен на этой неделе)">
                <div class="input-group-append">
                    <span class="input-group-text">&#8372;</span>
                </div>
            </div>
        </td>

        <td>
            <div class="input-group input-group-sm">
                <input type="number" step="0.01" class="form-control" disabled aria-label="Остаток (сколько осталось на этой неделе)">
                <div class="input-group-append">
                    <span class="input-group-text">&#8372;</span>
                </div>
            </div>
        </td>

    </tr>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'

export default {
    props: {
        pupil: {
            type: Object,
            required: true
        }
    },

    computed: {
        ...mapGetters([
            'activeDays',
            'pupilDishes',
            'dishes'
        ]),

        shortName () {
            return `${this.pupil.first_name} ${this.pupil.last_name.charAt(0)}.`
        }
    },

    methods: {
        ...mapActions([
            'deleteDayDishPupil'
        ]),

        fromFloatToInt (number) {
            return number
        },

        pay ($event) {
            const value = $event.target.value
            console.log('value: ' + value)
            console.log(typeof(value))
            this.$snotify.success({
                title: 'Important message',
                body: 'Hello user! This is a notification!'
            })
        },

        removeDish (dayId, dishId) {
            this.deleteDayDishPupil({
                dayId,
                dishId,
                pupilId: this.pupil.id
            })
        },

        addDish (dayId) {
            console.log('adding dish...')
        },

        popoverContent () {
            return 
            `<b-list-group>
                <b-list-group-item v-for="dish in dishes"> {{ dish.abbr }}</b-list-group-item>
            </b-list-group>`
        }
    }
}
</script>

<style>
    /* .dish-btn { */
        /* margin: 0 2px; */
    /* } */
</style>
