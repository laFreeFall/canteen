<template>
    <b-row class="justify-content-center">
        <v-wait for="loading week">
            <template slot="waiting">
                <orbit-spinner
                    :animation-duration="1500"
                    :size="300"
                    :color="'#007bff'"
                    class="text-center"
                >
                </orbit-spinner>
                <p class="text-muted text-center">
                    Данные загружаются...
                </p>
            </template>
            <b-col md="12" v-if="!isLoading">
                <!-- Основная таблица с отчётом за неделю -->
                <b-card id="table-report">
                    <div slot="header">
                        <h5 class="text-center align-middle">
                            <router-link
                                :to="{
                                    name: 'report',
                                    params: {
                                        id: prevPage.number
                                    }
                                }"
                                active-class="active"
                                exact
                                class="nav-item"
                            >
                                <icon
                                    name="chevron-circle-left"
                                    scale="2"
                                    class="float-left"
                                    :class="{ 'icon-disabled': !prevPage.available }"
                                    v-tooltip="prevPage.tooltipText"
                                >
                                </icon>
                            </router-link>
                            <!-- forward -->
                            <icon
                                name="reply-all"
                                flip="horizontal"
                                scale="1.5"
                                class="text-primary icon-button"
                                v-tooltip="'Перенести данные с прошлой недели'"
                                v-b-modal.moveDataModal
                            >
                            </icon>

                        {{ week.id }} неделя ({{ activeDays[0].number | withLeadingZero }}.{{ activeDays[0].month.number | withLeadingZero }} - {{ activeDays[activeDays.length - 1].number  | withLeadingZero }}.{{ activeDays[activeDays.length - 1].month.number  | withLeadingZero }})
                            
                            <a :href="`http://newcanteen.loc/reports/${weekId}/download`" download>
                                <icon
                                    name="file-word"
                                    scale="1.5"
                                    v-tooltip="'Скачать отчёт'"
                                    class="text-primary icon-button"
                                >
                                </icon>
                            </a>

                            
                            <router-link
                                :to="{
                                    name: 'report',
                                    params: {
                                        id: nextPage.number
                                    }
                                }"
                                active-class="active"
                                exact
                                class="nav-item"
                            >
                                <icon
                                    name="chevron-circle-right"
                                    scale="2"
                                    class="float-right"
                                    :class="{ 'icon-disabled': !nextPage.available }"
                                    v-tooltip="nextPage.tooltipText"
                                >
                                </icon>
                            </router-link>
                        </h5>
                    </div>
                    <b-alert
                        :show="!pupilsInitialBalances"
                        dismissible
                        fade
                        variant="warning"
                        class="text-center"
                    >
                        <icon name="exclamation-triangle"></icon>
                        <strong>Внимание!</strong>
                        Данные о питании учеников на этой неделе отсутствуют.
                        <icon
                            name="reply-all"
                            flip="horizontal"
                            v-b-modal.moveDataModal
                            class="c-pointer"
                            v-tooltip="'Перенести данные'"
                        >
                        </icon>
                        Перенесите их с предыдущей недели для корректной работы приложения.
                    </b-alert>
                    <b-alert
                        :show="!dishesPrices"
                        dismissible
                        fade
                        variant="warning"
                        class="text-center"
                    >
                        <icon name="exclamation-triangle"></icon>
                        <strong>Внимание!</strong>
                        Данные о ценах на блюда на этой неделе отсутствуют.
                        
                        Заполните их <a href="#" v-scroll-to="'#table-dishes-prices'" @click.prevent class="alert-link">в таблице внизу</a> для корректной работы приложения.
                        <!-- Заполните их <a href="#table-dishes-prices" @click.prevent="scrollTo('table-dishes-prices')" class="alert-link">в таблице внизу</a> для корректной работы приложения. -->
                    </b-alert>
                    <table class="table table-bordered text-center">
                        <thead>
                            <tr>
                                <th scope="col" class="align-middle col-pupil">
                                    Ученик
                                </th>

                                <th scope="col" class="align-middle col-paid">
                                    Заплатил
                                </th>

                                <th
                                    scope="col"
                                    v-for="day in activeDays"
                                    :key="day.id"
                                    class="align-middle col-day"
                                    :class="{ 'col-day-active': day.id === currentDay.id }"
                                >
                                    {{ day.name.title }}
                                    <br>
                                    {{ day.number | withLeadingZero }}.{{ day.month.number | withLeadingZero }}
                                </th>

                                <th scope="col" class="align-middle col-wasted">
                                    Потратил
                                </th>

                                <th scope="col" class="align-middle col-debt">
                                    Долг
                                </th>

                                <th scope="col" class="align-middle col-left">
                                    Остаток
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- <report-pupil v-for="pupil in pupils" :key="pupil.id" :pupil="pupil"></report-pupil> -->
                                <tr
                                    v-for="pupil in pupils"
                                    :key="pupil.id"
                                    v-if="isPupilShown(pupil)"
                                >
                                    <!-- <th scope="row" class="text-left">
                                        {{ index }}
                                    </th> -->
                                    <td class="align-middle text-left">
                                        {{ `${pupil.last_name} ${pupil.first_name.charAt(0)}.` }}
                                    </td>
                                    <td>
                                        <b-input-group size="sm" :append="currencySymbol">
                                            <b-form-input
                                                type="number"
                                                step="0.01"
                                                @blur="pay($event, pupil)"
                                                @keyup.enter="pay($event, pupil)"
                                                @change="pay($event, pupil)"
                                                :value="pupilPayment(pupil.id) === 0 ? '' : formatMoney(pupilPayment(pupil.id))"
                                                :placeholder="pupilPayment(pupil.id) === 0 ? formatMoney(0) : ''"
                                            >
                                            </b-form-input>
                                        </b-input-group>
                                    </td>
                                    <td
                                        v-for="day in activeDays"
                                        :key="day.id"
                                        class="align-middle dish-btns-parent"
                                        :class="{ 'col-day-active': day.id === currentDay.id }"
                                    >
                                        <b-button-group class="btn-group-xs">
                                            <b-button variant="outline-primary"
                                                class="dish-btn"
                                                v-for="(dish, index) in pupilDishes(pupil.id, day.id)" :key="index"
                                                v-tooltip="dish.entity.title + ': ' + formatMoney(dish.price, true)"
                                                @click="showRemoveDishModal(day, dish, pupil)"
                                            >
                                                {{ dish.entity.abbr }}
                                            </b-button>
                                        </b-button-group>
                                        <b-button variant="outline-dark"
                                            class="dish-btn btn-xs"
                                            v-if="pupilAbsence(pupil.id, day.id)"
                                            v-tooltip="'Ученик отсутствовал'"
                                            @click="showRemoveAbsenceModal(pupilAbsence(pupil.id, day.id))"
                                        >
                                            Н
                                        </b-button>
                                        <!-- v-b-popover.click.html="popoverContent" -->
                                        <v-popover
                                            placement="right"
                                            container=".container-fluid"
                                            class="d-inline-block"
                                        >
                                            <b-button variant="outline-primary"
                                                    class="btn-xs dish-btn add-dish-btn"
                                                    :id="`add-dish-${pupil.id}-${day.number}`"
                                                    title="Добавить блюдо"
                                                    
                                                >
                                                    <span class="add-dish-btn-sign">
                                                        <strong>+</strong>
                                                    </span>
                                            </b-button>

                                            <template slot="popover" >
                                                <h6>Шаблоны</h6>
                                                <b-list-group>
                                                    <b-list-group-item
                                                        v-for="template in templates"
                                                        :key="template.id"
                                                        @click="applyTemplateToPupils(day, pupil, template)"
                                                    >
                                                        <icon name="cubes"></icon>
                                                        {{ template.title }}
                                                    </b-list-group-item>
  
                                                </b-list-group>
                                                <h6>Блюда</h6>
                                                <b-list-group>
                                                    <b-list-group-item
                                                        v-for="dish in dishes"
                                                        :key="dish.id"
                                                        v-if="isDishShown(dish)"
                                                        @click="addDishToPupil(day, dish, pupil)"
                                                    >
                                                        <icon :name="dish.icon"></icon>
                                                        {{ dish.title }}
                                                    </b-list-group-item>
                                                </b-list-group>
                                                <h6>Остальное</h6>
                                                <b-list-group>
                                                    <b-list-group-item @click="addAbsenceToPupil(day, pupil)">
                                                        <icon name="times-circle"></icon>
                                                        Н ({{ pupil.gender ? 'отсутствовал' : 'отсутствовала' }})
                                                    </b-list-group-item>
                                                </b-list-group>
                                            </template>
                                        </v-popover>
                                    </td>

                                    <td>
                                        <div class="input-group input-group-sm">
                                            <input
                                                type="number"
                                                step="0.01"
                                                :value="formatMoney(pupilWasted(pupil.id))"
                                                class="form-control"
                                                disabled
                                                aria-label="Потратил (сколько потратил на этой неделе)"
                                            >
                                            <div class="input-group-append">
                                                <span class="input-group-text">{{ currencySymbol }}</span>
                                            </div>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="input-group input-group-sm">
                                            <input
                                                type="number"
                                                :value="pupilBalance(pupil.id) < 0  ? formatMoney(pupilBalance(pupil.id), false, true) : formatMoney(0)"
                                                class="form-control"
                                                :class="{ 'debt-active': pupilBalance(pupil.id) < 0 }"
                                                disabled
                                                aria-label="Долг (сколько должен на этой неделе)"
                                            >
                                            <div class="input-group-append">
                                                <span class="input-group-text">{{ currencySymbol }}</span>
                                            </div>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="input-group input-group-sm">
                                            <input
                                                type="number"
                                                :value="pupilBalance(pupil.id) > 0  ? formatMoney(pupilBalance(pupil.id)) : formatMoney(0)"
                                                class="form-control"
                                                :class="{ 'surplus-active': pupilBalance(pupil.id) > 0 }"
                                                disabled
                                                aria-label="Остаток (сколько осталось на этой неделе)"
                                            >
                                            <div class="input-group-append">
                                                <span class="input-group-text">{{ currencySymbol }}</span>
                                            </div>
                                        </div>
                                    </td>

                                </tr>
                                <tr>
                                    <td class="text-left">
                                        <strong>Итого:</strong>
                                    </td>
                                    <td>
                                        <div class="input-group input-group-sm">
                                            <input
                                                type="number"
                                                :value="formatMoney(pupilsPayments)"
                                                class="form-control"
                                                disabled
                                                aria-label="Сумма платежей"
                                            >
                                            <div class="input-group-append">
                                                <span class="input-group-text">{{ currencySymbol }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td v-for="day in activeDays" :key="day.id">
                                        <b-button-group class="btn-group-xs">
                                            <b-button variant="outline-primary"
                                                class="dish-btn"
                                                v-for="dish in dayTotalDishes(day.id)" :key="dish.id"
                                                v-if="dish.count"
                                                v-tooltip="dish.title + ': ' + dish.count + ' * ' + formatMoney(dish.price, true) + ' = '  + formatMoney(dish.price * dish.count, true)"
                                            >
                                                {{ dish.abbr }} <span class="small text-muted">{{ dish.count }}</span>
                                            </b-button>
                                        </b-button-group>
                                    </td>
                                    <td>
                                        <div class="input-group input-group-sm">
                                            <input
                                                type="number"
                                                :value="formatMoney(pupilsWasteds)"
                                                class="form-control"
                                                disabled
                                                aria-label="Сумма потраченных денег"
                                            >
                                            <div class="input-group-append">
                                                <span class="input-group-text">{{ currencySymbol }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group input-group-sm">
                                            <input
                                                type="number"
                                                :value="formatMoney(pupilsDebts, false, true)"
                                                class="form-control"
                                                :class="{ 'debt-active': pupilsDebts > 0 }"
                                                disabled
                                                aria-label="Сумма долга"
                                            >
                                            <div class="input-group-append">
                                                <span class="input-group-text">{{ currencySymbol }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group input-group-sm">
                                            <input
                                                type="number"
                                                :value="formatMoney(pupilsSurpluses)"
                                                class="form-control"
                                                :class="{ 'surplus-active': pupilsSurpluses > 0 }"
                                                disabled
                                                aria-label="Сумма остатка"
                                            >
                                            <div class="input-group-append">
                                                <span class="input-group-text">{{ currencySymbol }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <!-- Долг/Остаток = Баланс (на начало этой недели) + Заплатил - Потратил -->
                                </tr>
                        </tbody>
                    </table>
                </b-card>

                <!-- Таблица с ценами на еду на этой неделе -->
                <b-card class="mt-3" id="table-dishes-prices" ref="table-dishes-prices">
                    <div slot="header">
                        <h5 class="text-center">
                            Цены на еду на {{ this.weekId }}-й неделе ({{ activeDays[0].number | withLeadingZero }}.{{ activeDays[0].month.number | withLeadingZero }} - {{ activeDays[activeDays.length - 1].number  | withLeadingZero }}.{{ activeDays[activeDays.length - 1].month.number  | withLeadingZero }})
                        </h5>
                    </div>
                    <table class="table table-bordered text-center">
                        <thead>
                            <tr>
                                <th scope="col" class="align-middle dish-table-dish">Блюдо</th>
                                <th
                                    scope="col"
                                    v-for="day in activeDays"
                                    :key="day.id"
                                    class="align-middle dish-table-price"
                                    :class="{ 'col-day-active': day.id === currentDay.id }"
                                >
                                    {{ day.name.title }}
                                    <br>
                                    {{ day.number | withLeadingZero }}.{{ day.month.number | withLeadingZero }}
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="dish in dishes"
                                :key="dish.id"
                                v-if="isDishShown(dish)"
                            >
                                <td class="align-middle text-left">
                                    <icon :name="dish.icon" class="dish-icon-bottom mr-2"></icon>
                                    {{ dish.title }}
                                </td>
                                <td
                                    v-for="day in activeDays"
                                    :key="day.id"
                                    class="align-middle"
                                    :class="{ 'col-day-active': day.id === currentDay.id }"
                                >
                                    <b-input-group size="sm" :append="currencySymbol">
                                        <b-form-input
                                            type="number"
                                            step="0.01"
                                            :value="dishPrice(day.id, dish.id) === 0 ? '' : formatMoney(dishPrice(day.id, dish.id))"
                                            :placeholder="dishPrice(day.id, dish.id) === 0 ? formatMoney(0) : ''"
                                            class="form-control"
                                            @blur="setDishPrice($event, day, dish)"
                                            @keyup.enter="setDishPrice($event, day, dish)"
                                            @change="setDishPrice($event, day, dish)"
                                            aria-label="Цена на блюдо"
                                        >
                                        </b-form-input>
                                    </b-input-group>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </b-card>
                <b-alert show variant="light" class="text-center">
                    <a href="#" v-scroll-to="'#table-report'" @click.prevent class="alert-link">Вернуться наверх страницы</a>
                </b-alert>

                <b-modal ref="removeDishModal" hide-footer title="Удаление блюда">
                    <b-alert show variant="warning">
                        Вы действительно хотите удалить блюдо?
                    </b-alert>
                    <b-btn variant="outline-primary" @click="removeDish()" class="float-left">Да, удалить</b-btn>
                    <b-btn variant="outline-danger" @click="hideRemoveDishModal()" class="float-right">Отменить</b-btn>
                </b-modal>

                <b-modal ref="removeAbsenceModal" hide-footer title="Удаление Н">
                    <b-alert show variant="warning">
                        Вы действительно хотите удалить Н у ученика?
                    </b-alert>
                    <b-btn variant="outline-primary" @click="removeAbsence()" class="float-left">Да, удалить</b-btn>
                    <b-btn variant="outline-danger" @click="hideRemoveAbsenceModal()" class="float-right">Отменить</b-btn>
                </b-modal>

                <b-modal id="moveDataModal" ref="moveDataModal" hide-footer title="Перенос данных">
                    <b-alert show variant="warning">
                        Вы действительно хотите перенести данные на эту неделю?
                    </b-alert>
                    <b-btn variant="outline-primary" @click="moveData()" class="float-left">Да, перенести</b-btn>
                    <b-btn variant="outline-danger" @click="$refs.moveDataModal.hide()" class="float-right">Отменить</b-btn>
                </b-modal>
            </b-col>
        </v-wait>
    </b-row>

</template>

<script>
    import { mapGetters, mapActions } from 'vuex'
    import { mapWaitingGetters, mapWaitingActions } from 'vue-wait'
    import { OrbitSpinner } from 'epic-spinners'
    import { money } from './../mixins/money'

    export default {
        components: {
            OrbitSpinner
        },

        props: ['weekId'],

        mixins: [money],

        data () {
            return {
                currentDayDishPupilEntity: {
                    day: {},
                    dish: {},
                    pupil: {}
                },
                currentAbsenceEntity: {}
            }
        },

        computed: {
            ...mapGetters([
                'week',
                'currentWeek',
                'currentDay',
                'pupils',
                'dishes',
                'activeDays',
                'pupilDishes',
                'pupilAbsence',
                'dayTotalDishes',
                'pupilInitialBalance',
                'pupilWasted',
                'pupilBalance',
                'weeksLimits',
                'pupilPayment',
                'pupilsPayments',
                'pupilsWasteds',
                'pupilsDebts',
                'pupilsSurpluses',
                'dishPrice',
                'dishesPrices',
                'pupilsInitialBalances',
                'isDishPriceSet',
                'templates',
                'isPupilShown',
                'isDishShown'
            ]),

            ...mapWaitingGetters({
                'isLoading': 'loading week'
            }),

            nextPage () {
                const number = parseInt(this.weekId) + 1
                const available = number >= this.weeksLimits.begin && number <= this.weeksLimits.end
                const tooltipText = available ? `Перейти на следующую страницу (Неделя №${number})`
                    : 'Переход невозможен. Вы находитесь на последней странице'
                return {
                    number,
                    available,
                    tooltipText
                }
            },

            prevPage () {
                const number = parseInt(this.weekId) - 1
                const available = number >= this.weeksLimits.begin && number <= this.weeksLimits.end
                const tooltipText = available ? `Перейти на предыдущую страницу (Неделя №${number})`
                    : 'Переход невозможен. Вы находитесь на первой странице'
                return {
                    number,
                    available,
                    tooltipText
                }
            }
        },

        methods: {
            ...mapActions([
                'fetchWeek',
                'addDayDishPupil',
                'deleteDayDishPupil',
                'addAbsence',
                'deleteAbsence',
                'moveWeekData',
                'addPayment',
                'addDayDishPrice',
                'updateDayDishPrice',
                'applyTemplate'
            ]),
            ...mapWaitingActions({
                fetchWeek: 'loading week'
            }),

            loadReportData (weekId = 1) {
                this.fetchWeek(weekId)
                    .then(seponse => {
                        if (! this.pupilsInitialBalances) {
                            this.$snotify.warning('Мы не нашли данных об учениках. Перенесите их с прошлой недели.', 'Перенос данных', { timeout: 5000 })
                        }
                        if (! this.dishesPrices)
                            this.$snotify.warning('Мы не нашли цены на блюда. Запишите их в таблицу снизу.', 'Цены на блюда', { timeout: 5000 })

                    })
                    .catch(error => {
                        this.$snotify.error('При загрузке данных ошибка.', 'Ошибка')
                    })
            },

            addDishToPupil (day, dish, pupil) {
                this.setCurrentDayDishPupilEntity(day, dish, pupil)
                
                this.addDayDishPupil({
                    day_id: day.id,
                    dish_id: dish.id,
                    pupil_id: pupil.id
                })
                    .then(response => {
                        console.log(day)
                        const dishAction = pupil.gender ? dish.action : dish.action + 'а'
                        this.$snotify.success(`${pupil.last_name} ${pupil.first_name} ${dishAction} ${dish.accusative} ${day.name.action}!`, '')
                    })
                    .catch(error => {
                        const dishAction = dish.gender ? 'добавлен' : 'добавлена'
                        const pupilTitle = pupil.gender ? 'ученику' : 'ученице'
                        this.$snotify.error(`${dish.title} не была ${dishAction} ${pupilTitle} ${pupil.last_name} ${pupil.first_name} ${day.name.action}`, 'Ошибка')
                    })
                
            },

            setCurrentDayDishPupilEntity (day, dish, pupil) {
                this.currentDayDishPupilEntity.day = day
                this.currentDayDishPupilEntity.dish = dish
                this.currentDayDishPupilEntity.pupil = pupil
            },

            resetCurrentDayDishPupilEntity () {
                this.currentDayDishPupilEntity.day = {}
                this.currentDayDishPupilEntity.dish = {}
                this.currentDayDishPupilEntity.pupil = {}
            },

            showRemoveDishModal (day, dish, pupil) {
                this.setCurrentDayDishPupilEntity(day, dish, pupil)
                this.$refs.removeDishModal.show()
            },

            hideRemoveDishModal () {
                this.resetCurrentDayDishPupilEntity()
                this.$refs.removeDishModal.hide()
            },

            removeDish () {
                this.deleteDayDishPupil(this.currentDayDishPupilEntity.dish.id)
                    .then(response => {
                        this.$refs.removeDishModal.hide()
                        const dishAction = this.currentDayDishPupilEntity.dish.entity.gender ? 'был удален' : 'была удалена'
                        const pupilTitle = this.currentDayDishPupilEntity.pupil.gender ? 'ученика' : 'ученицы'
                        this.$snotify.success(`${this.currentDayDishPupilEntity.dish.entity.title} ${dishAction} у ${pupilTitle} ${this.currentDayDishPupilEntity.pupil.last_name} ${this.currentDayDishPupilEntity.pupil.first_name} ${this.currentDayDishPupilEntity.day.name.action}`, '')
                    })
                    .catch(error => {
                        this.$snotify.error('При удалении блюда произошла ошибка.', 'Ошибка')
                    })
            },

            addAbsenceToPupil (day, pupil) {
                this.setCurrentAbsenceEntity(day, pupil)
                this.addAbsence({
                    day_id: day.id,
                    pupil_id: pupil.id
                })
                    .then(response => {
                        const pupilAction = pupil.gender ? 'отсутствовал' : 'отсутствовала'
                        this.$snotify.success(`${pupil.last_name} ${pupil.first_name} ${pupilAction}!`, '')
                    })
                    .catch(error => {
                        this.$snotify.error('При добавлении Н произошла ошибка.', 'Ошибка')
                    })
            },

            setCurrentAbsenceEntity (entity) {
                this.currentAbsenceEntity = entity
            },

            resetCurrentAbsenceEntity () {
                this.currentAbsenceEntity = {}
            },

            showRemoveAbsenceModal (entity) {
                this.setCurrentAbsenceEntity(entity)
                this.$refs.removeAbsenceModal.show()
            },

            hideRemoveAbsenceModal () {
                this.resetCurrentAbsenceEntity()
                this.$refs.removeAbsenceModal.hide()
            },

            removeAbsence () {
                console.log(this.currentAbsenceEntity)
                this.deleteAbsence({ id: this.currentAbsenceEntity.id })
                    .then(response => {
                        this.$refs.removeAbsenceModal.hide()
                        this.$snotify.success('Н была успешна удалена!', '')
                    })
                    .catch(error => {
                        this.$snotify.error('При удалении Н произошла ошибка.', 'Ошибка')
                    })
            },

            moveData () {
                this.moveWeekData(this.weekId)
                    .then(response => {
                        this.$refs.moveDataModal.hide()
                        this.$snotify.success(`Данные с ${this.weekId - 1} недели были успешно перенесены!`, '')
                    })
                    .catch(error => {
                        this.$snotify.error('При переносе данных произошла ошибка.', 'Ошибка')
                    })  
            },

            pay ($event, pupil) {
                this.addPayment({
                    pupil_id: pupil.id,
                    week_id: this.weekId,
                    amount: this.transformInputMoney($event)
                })
                    .then(response => {
                        const paymentAmount = this.formatMoney(this.transformInputMoney($event), true)
                        const pupilAction = pupil.gender ? 'заплатил' : 'заплатила'
                        this.$snotify.success(`${pupil.last_name} ${pupil.first_name} ${pupilAction} ${paymentAmount} на этой неделе!`, '')
                    })
                    .catch(error => {
                        this.$snotify.error('При проведении платежа произошла ошибка.', 'Ошибка')
                    })  
            },

            setDishPrice ($event, day, dish) {
                const isSet = this.isDishPriceSet(day.id, dish.id)
                console.log(isSet)
                if (isSet) {
                    this.updateDayDishPrice({
                        id: isSet,
                        price: this.transformInputMoney($event)
                    })
                        .then(response => {
                            const dishPrice = this.formatMoney(this.transformInputMoney($event), true)
                            this.$snotify.success(`Цена на ${dish.accusative} ${day.name.action} была изменена на ${dishPrice}`, '')
                        })
                        .catch(error => {
                            this.$snotify.error('При изменении цены на блюдо произошла ошибка.', 'Ошибка')
                        }) 
                } else {
                    this.addDayDishPrice({
                        day_id: day.id,
                        dish_id: dish.id,
                        price: this.transformInputMoney($event)
                    })
                        .then(response => {
                            const dishPrice = this.formatMoney(this.transformInputMoney($event), true)
                            this.$snotify.success(`Цена на ${dish.accusative} ${day.name.action} была установлена как ${dishPrice}`, '')
                        })
                        .catch(error => {
                            this.$snotify.error('При установке цены на блюдо произошла ошибка.', 'Ошибка')
                        }) 
                }
            },

            applyTemplateToPupils (day, pupil, template) {
                this.applyTemplate({ 
                    'day_id': day.id,
                    'pupil_id': pupil.id,
                    'template_id': template.id
                })
                    .then(response => {
                        this.$snotify.success(`${template.title} был успешно применён ${day.name.action}!`, '')
                    })
                    .catch(error => {
                        this.$snotify.error('При применении шаблона произошла ошибка.', 'Ошибка')
                    }) 
            }
        },

        created () {
            if(this.weekId !== this.week.id) {
                this.loadReportData(this.weekId)
            }
        },

        beforeRouteUpdate (to, from, next) {
            if (to.params.id >= this.weeksLimits.begin && to.params.id <= this.weeksLimits.end) {
                this.$snotify.clear()
                this.loadReportData(to.params.id)
                next()
            }
        }
    }
</script>

<style scoped>
    .table th {
        padding: .15rem;
    }

    .col-pupil {
        width: 10%;
    }

    .col-day {
        width: 10%;
    }

    .col-day-active {
        background-color: rgba(100, 255, 150, 0.1);
    }

    .col-paid,
    .col-wasted,
    .col-debt,
    .col-left {
        width: 10%;
    }

    .add-dish-btn {
        display: none;
    }
    
    .dish-btns-parent:hover .add-dish-btn {
        display: block;
    }

    .add-dish-btn-sign {
        display: inline-block;
        transition: transform .5s ease-in-out;
    }

    .add-dish-btn-sign:hover {
        -webkit-transform: rotate(360deg);
        transform: rotate(360deg);
    }

    .popover .list-group-item {
        background-color: #fff;
        transition: background-color .5s ease-in-out;
        cursor: default;
        text-align: left;
        padding: .4rem .75rem;
    }

    .popover .list-group-item:hover {
        background-color: #ccc;
        transition: background-color .5s ease-in-out;
        cursor: pointer;
    }

    .table td {
        padding: .5rem .6rem;
    }

    .input-group .debt-active {
        background-color: rgba(255, 0, 0, .2);
    }

    .input-group .surplus-active {
        background-color: rgba(0, 255, 0, .2);
    }

    .icon-disabled {
        cursor: default;
        opacity: .3;
    }

    /* .icon-button { */
        /* color: #007bff; */
    /* } */

    .icon-button:hover {
        cursor: pointer;
    }

    .dish-table-dish {
        width: 15%;
    }
</style>
