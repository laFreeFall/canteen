import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios'

Vue.use(Vuex)

export default new Vuex.Store({
    state: {
        pupils: [],
        grades: [],
        dishes: [],
        calendar: [],
        daysNames: [],
        currentDate: {},
        week: {
            days: []
        },
        dayDishPupil: [],
        dayDishPrice: [],
        absences: [],
        initialBalances: [],
        currentBalances: [],
        payments: [],
        weeksLimits: {},
        templates: [],
        dishPupilTemplate: [],
        loaded: false
    },

    mutations: {
        SET_INITIAL_STATE (state, initialState) {
            state.pupils = initialState.pupils
            state.grades = initialState.grades
            state.dishes = initialState.dishes
            state.calendar = initialState.calendar
            state.daysNames = initialState.daysNames
            state.currentDate = initialState.currentDate
            state.weeksLimits = initialState.weeksLimits
            state.templates = initialState.templates
            state.dishPupilTemplate = initialState.dishPupilTemplate
            state.loaded = true
        },

        SET_WEEK (state, payload) {
            state.dayDishPupil = payload.dayDishPupil
            state.dayDishPrice = payload.dayDishPrice
            state.absences = payload.absences
            state.initialBalances = payload.balances
            state.payments = payload.payments
            state.week = payload.week
        },

        ADD_DAY_DISH_PUPIL (state, entity) {
            state.dayDishPupil.push(entity)
        },

        DELETE_DAY_DISH_PUPIL (state, id) {
            /*
            state.dayDishPupil.splice(state.dayDishPupil.findIndex(record =>
                record.day_id === dayDishPupilEntity.day_id &&
                record.dish_id === dayDishPupilEntity.dish_id &&
                record.pupil_id === dayDishPupilEntity.pupil_id
            ), 1)
            */
           state.dayDishPupil.splice(state.dayDishPupil.findIndex(
               record => record.id === id
           ), 1)
        },

        ADD_ABSENCE (state, absence) {
            state.absences.push(absence)

            const itemsToDelete = state.dayDishPupil.filter(record =>
                record.day_id === absence.day_id && record.pupil_id === absence.pupil_id
            )

            const idsToDelete = itemsToDelete.map(itemToDelete => itemToDelete.id)
            
            state.dayDishPupil = state.dayDishPupil.filter(record =>
                !idsToDelete.includes(record.id)
            )
        },

        DELETE_ABSENCE (state, id) {
            state.absences.splice(state.absences.findIndex(record => record.id === id), 1)
        },

        ADD_PUPIL (state, pupil) {
            state.pupils.push(pupil)
        },

        EDIT_CALENDAR (state, payload) {
            state.calendar = payload.calendar
        },

        MOVE_WEEK_DATA (state, payload) {
            state.initialBalances = payload
        },

        ADD_PAYMENT (state, payload) {
            const payment = state.payments.find(payment => payment.pupil_id === payload.pupil_id)
            if (payment !== undefined) {
                payment.amount = payload.amount
            } else {
                state.payments.push(payload)
            }
        },

        ADD_DAY_DISH_PRICE (state, dayDishPrice) {
            state.dayDishPrice.push(dayDishPrice)
        },

        UPDATE_DAY_DISH_PRICE (state, payload) {
            state.dayDishPrice.find(record => record.id === payload.id).price = payload.price
        },

        CHANGE_DAY_DISH_PRICE (state, payload) {
            const dayDishPriceRecord = state.dayDishPrice.find(record =>
                record.dish_id === payload.dish_id
                && record.day_id === payload.day_id
            )
            if (dayDishPriceRecord !== undefined) {
                console.log(dayDishPriceRecord)
                dayDishPriceRecord.price = payload.price
            } else {
                state.dayDishPrice.push(payload)
            }
        },

        ADD_DISH_PUPIL_TEMPLATE (state, dishPupilTemplate) {
            state.dishPupilTemplate.push(dishPupilTemplate)
        },

        DELETE_DISH_PUPIL_TEMPLATE (state, id) {
            state.dishPupilTemplate.splice(state.dishPupilTemplate.findIndex(record => record.id === id), 1)
        },

        UPDATE_TEMPLATE (state, payload) {
            state.templates.find(record => record.id === payload.id).title = payload.title
        },

        DELETE_TEMPLATE (state, id) {
            state.templates.splice(state.templates.findIndex(record => record.id === id), 1)
        },

        APPLY_TEMPLATE (state, dayDishPupilItems) {
            dayDishPupilItems.forEach(dayDishPupilItem => state.dayDishPupil.push(dayDishPupilItem))
        },

        ADD_TEMPLATE (state, template) {
            state.templates.push(template)
        },

        CLONE_TEMPLATE (state, payload) {
            state.templates.push(payload.template)
            payload.templateItems.forEach(templateItem => state.dishPupilTemplate.push(templateItem))
        },

        ADD_PUPIL (state, pupil) {
            state.pupils.push(pupil)
        },

        UPDATE_PUPIL (state, updatedPupil) {
            state.pupils.splice(state.pupils.findIndex(pupil => pupil.id === updatedPupil.id), 1)
            state.pupils.push(updatedPupil)
        },

        ADD_DISH (state, dish) {
            state.dishes.push(dish)
        },

        UPDATE_DISH (state, updatedDish) {
            state.dishes.splice(state.dishes.findIndex(dish => dish.id === updatedDish.id), 1)
            state.dishes.push(updatedDish)
        },

        SWAP_DISHES (state, payload) {
            console.log(payload)
            
            payload.dishes.forEach(dish => {
                // const dishToReorder = state.dishes.splice(state.dishes.find(dishEntity => dishEntity.id === dish.id))
                // state.dishes.push(dish)
                state.dishes.find(dishEntity => dishEntity.id === dish.id).order_id = dish.order_id
            })
            
        }
    },

    actions: {
        loadInitialState ({ commit }, initialState) {
            commit('SET_INITIAL_STATE', initialState)
        },

        fetchWeek ({ commit }, weekId) {
            return axios.get(`/reports/${weekId}`)
                .then(response => {
                    commit('SET_WEEK', response.data)
                    return response.data
                })
                .catch(error => {
                    console.log(error)
                    reject(error)
                })
        },

        addDayDishPupil ({ commit }, payload) {
            return axios.post('/daydishpupil', payload)
                .then(response => {
                    commit('ADD_DAY_DISH_PUPIL', response.data)
                    return response.data
                })
                .catch(error => {
                    console.log(error)
                    reject(error)
                })
        },
        
        deleteDayDishPupil ({ commit }, dayDishPupilId) {
            return axios.delete(`/daydishpupil/${dayDishPupilId}`)
            .then(response => {
                commit('DELETE_DAY_DISH_PUPIL', dayDishPupilId)
                return response.data
            })
            .catch(error => {
                console.log(error)
                reject(error)
            })
        },

        addDayDishPrice ({ commit }, payload) {
            return axios.post('/daydishprice', payload)
                .then(response => {
                    commit('ADD_DAY_DISH_PRICE', response.data)
                    return response.data
                })
                .catch(error => {
                    console.log(error)
                    reject(error)
                })
        },

        updateDayDishPrice ({ commit }, payload) {
            return axios.patch(`/daydishprice/${payload.id}`, payload)
                .then(response => {
                    commit('UPDATE_DAY_DISH_PRICE', response.data)
                    return response.data
                })
                .catch(error => {
                    console.log(error)
                    reject(error)
                })
        },

        addAbsence ({ commit }, payload) {
            return axios.post('/absences', payload)
                .then(response => {
                    commit('ADD_ABSENCE', response.data)
                    return response.data
                })
                .catch(error => {
                    console.log(error)
                    reject(error)
                })
        },

        deleteAbsence ({ commit }, payload) {
            return axios.delete(`/absences/${payload.id}`)
                .then(response => {
                    commit('DELETE_ABSENCE', payload.id)
                    return response.data
                })
                .catch(error => {
                    console.log(error)
                    reject(error)
                })
        },

        addPupil ({ commit }, payload) {
            return axios.post('/pupils', payload)
                .then(response => {
                    commit('ADD_PUPIL', response.data)
                    return response.data
                })
                .catch(error => {
                    console.log(error)
                    reject(error)
                })
        },

        addActiveDay ({ commit }, day) {
            return axios.post(`/days/${day.id}`)
                .then(response => {
                    commit('EDIT_CALENDAR', response.data)
                    return response.data
                })
                .catch(error => {
                    console.log(error)
                    reject(error)
                })
        },

        deleteActiveDay ({ commit }, day) {
            return axios.delete(`/days/${day.id}`)
                .then(response => {
                    commit('EDIT_CALENDAR', response.data)
                    return response.data
                })
                .catch(error => {
                    console.log(error)
                    reject(error)
                })
        },

        moveWeekData ({ commit }, weekId) {
            return axios.post(`/balances/${weekId}/move`)
                .then(response => {
                    commit('MOVE_WEEK_DATA', response.data)
                    return response.data
                })
                .catch(error => {
                    console.log(error)
                    reject(error)
                })
        },

        addPayment ({ commit }, payload) {
            return axios.post(`/payments/store`, payload)
                .then(response => {
                    commit('ADD_PAYMENT', response.data)
                    return response.data
                })
                .catch(error => {
                    console.log(error)
                    reject(error)
                })
        },

        addDishPupilTemplate ({ commit }, payload) {
            console.log(payload)
            return axios.post('/dishpupiltemplate', payload)
                .then(response => {
                    commit('ADD_DISH_PUPIL_TEMPLATE', response.data)
                    return response.data
                })
                .catch(error => {
                    console.log(error)
                    reject(error)
                })
        },
        
        deleteDishPupilTemplate ({ commit }, payload) {
            console.log(payload)
            return axios.delete(`/dishpupiltemplate/${payload.id}`)
                .then(response => {
                    commit('DELETE_DISH_PUPIL_TEMPLATE', payload.id)
                    return response.data
                })
                .catch(error => {
                    console.log(error)
                    reject(error)
                })
        },

        updateTemplate ({ commit }, payload) {
            return axios.patch(`/templates/${payload.id}`, payload)
                .then(response => {
                    commit('UPDATE_TEMPLATE', response.data)
                    return response.data
                })
                .catch(error => {
                    console.log(error)
                    reject(error)
                })
        },

        deleteTemplate ({ commit }, payload) {
            return axios.delete(`/templates/${payload.id}`)
                .then(response => {
                    commit('DELETE_TEMPLATE', payload.id)
                    return response.data
                })
                .catch(error => {
                    console.log(error)
                    reject(error)
                })
        },

        applyTemplate({ commit }, payload) {
            return axios.post(`/templates/${payload.template_id}/apply`, payload)
                .then(response => {
                    commit('APPLY_TEMPLATE', response.data)
                    return response.data
                })
                .catch(error => {
                    console.log(error)
                    reject(error)
                })
        },

        addTemplate({ commit }, payload) {
            return axios.post('/templates', payload)
                .then(response => {
                    commit('ADD_TEMPLATE', response.data)
                    return response.data
                })
                .catch(error => {
                    console.log(error)
                    reject(error)
                })
        },

        cloneTemplate({ commit }, payload) {
            return axios.post(`/templates/${payload.template_id}/clone`, payload)
                .then(response => {
                    commit('CLONE_TEMPLATE', response.data)
                    return response.data
                })
                .catch(error => {
                    console.log(error)
                    reject(error)
                })
        },

        addPupil({ commit }, payload) {
            return axios.post('/pupils', payload)
                .then(response => {
                    commit('ADD_PUPIL', response.data)
                    return response.data
                })
                .catch(error => {
                    console.log(error)
                    reject(error)
                })
        },

        updatePupil({ commit }, payload) {
            return axios.patch(`/pupils/${payload.id}`, payload)
                .then(response => {
                    commit('UPDATE_PUPIL', response.data)
                    return response.data
                })
                .catch(error => {
                    console.log(error)
                    reject(error)
                })
        },

        deletePupil({ commit }, payload) {
            return axios.delete(`/pupils/${payload.id}`)
                .then(response => {
                    commit('UPDATE_PUPIL', response.data)
                    return response.data
                })
                .catch(error => {
                    console.log(error)
                    reject(error)
                })
        },

        addDish({ commit }, payload) {
            return axios.post('/dishes', payload)
                .then(response => {
                    commit('ADD_DISH', response.data)
                    return response.data
                })
                .catch(error => {
                    console.log(error)
                    reject(error)
                })
        },

        updateDish({ commit }, payload) {
            return axios.patch(`/dishes/${payload.id}`, payload)
                .then(response => {
                    commit('UPDATE_DISH', response.data)
                    return response.data
                })
                .catch(error => {
                    console.log(error)
                    reject(error)
                })
        },

        deleteDish({ commit }, payload) {
            return axios.delete(`/dishes/${payload.id}`)
                .then(response => {
                    commit('UPDATE_DISH', response.data)
                    return response.data
                })
                .catch(error => {
                    console.log(error)
                    reject(error)
                })
        },

        swapDishes({ commit }, payload) {
            return axios.post('/dishes/swap', payload)
                .then(response => {
                    commit('SWAP_DISHES', response.data)
                    return response.data
                })
                .catch(error => {
                    console.log(error)
                    reject(error)
                })
        }
        
    },

    getters: {
        pupils (state) {
            return state.pupils.sort((a, b) => {
                const lastNameA = a.last_name.toLowerCase();
                const lastNameB = b.last_name.toLowerCase();
                if (lastNameA < lastNameB) {
                    return -1
                }
                if (lastNameA > lastNameB) {
                    return 1
                }

                const firstNameA = a.first_name.toLowerCase();
                const firstNameB = b.first_name.toLowerCase();
                if (firstNameA < firstNameB) {
                    return -1
                }
                if (firstNameA > firstNameB) {
                    return 1
                }

                return 0
            })
        },

        activePupils (state, getters) {
            return getters.pupils.filter(pupil => pupil.deleted_at === null)
        },

        inactivePupils (state, getters) {
            return getters.pupils.filter(pupil => pupil.deleted_at !== null)
        },

        isPupilShown: (state, getters) => (pupil) => {
            const pupilDishes = state.dayDishPupil.filter(dayDishPupilEntity =>
                dayDishPupilEntity.pupil_id === pupil.id)

            return !(pupilDishes.length === 0 && pupil.deleted_at !== null)
        },

        grades (state) {
            return state.grades
        },

        gradesForForm (state) {
            return state.grades.map(grade => {
                return {
                    value: grade.id,
                    text: grade.title
                }
            })
        },

        gendersForForm () {
            return [
                {
                    text: 'Мужской',
                    value: true
                },
                {
                    text: 'Женский',
                    value: false
                },
            ]
        },

        dishes (state) {
            return state.dishes.sort((a, b) => a.order_id - b.order_id)
        },

        activeDishes (state, getters) {
            return getters.dishes.filter(dish => dish.deleted_at === null)
        },

        inactiveDishes (state, getters) {
            return getters.dishes.filter(dish => dish.deleted_at !== null)
        },

        isDishShown: (state, getters) => (dish) => {
            const dishesPrices = state.dayDishPrice.filter(dayDishPriceEntity =>
                dayDishPriceEntity.dish_id === dish.id)

            return !(dishesPrices.length === 0 && dish.deleted_at !== null)
        },

        calendar (state) {
            return state.calendar
        },

        daysNames (state) {
            return state.daysNames
        },

        currentDate (state) {
            return state.currentDate
        },

        currentDay (state) {
            return state.currentDate.day
        },

        currentWeek (state) {
            return state.currentDate.week
        },

        currentMonth (state) {
            return state.currentDate.month
        },

        week (state) {
            return state.week
        },

        loaded (state) {
            return state.loaded
        },

        activeDays (state) {
            return state.week.days.filter(day => day.active)
        },

        dishPrice: (state) => (dayId, dishId) => {
            const dishPrice = state.dayDishPrice.find(record =>
                record.day_id === dayId
                && record.dish_id === dishId
            )
            return dishPrice === undefined ? 0 : dishPrice.price
        },

        isDishPriceSet: (state) => (dayId, dishId) => {
            const record = state.dayDishPrice.find(record =>
                record.day_id === dayId
                && record.dish_id === dishId
            )
            return record === undefined ? false : record.id
        },

        dishesPrices (state) {
            return state.dayDishPrice.length !== 0
        },

        pupilDishes: (state, getters) => (pupilId, dayId) => {
            return state.dayDishPupil
                .filter(record =>
                    record.day_id === dayId
                    && record.pupil_id === pupilId
                )
                .map(record => {
                    /*
                    const dish = state.dishes.find(dish =>
                        dish.id === record.dish_id
                    )
                    dish.price = getters.dishPrice(dayId, dish.id)
                    dish.dayDishPupil_id = record.id
                    return dish
                    */
                   const dish = state.dishes.find(dish => dish.id === record.dish_id)
                   return {
                        entity: dish,
                        price: getters.dishPrice(dayId, dish.id),
                        id: record.id
                    }

                   /*
                    return {
                        entity: state.dishes.find(dish => dish.id === record.dish_id),
                        price: getters.dishPrice(dayId, state.dishes.find(dish => dish.id === record.dish_id).id),
                        id: record.id
                    }
                    */
                })
                .sort((a, b) => a.entity.order_id - b.entity.order_id)
        },

        dayTotalDishes: (state) => (dayId) => {
            const dayDishPupilRecords = state.dayDishPupil.filter(record => record.day_id === dayId)
            const dayDishPriceRecords = state.dayDishPrice.filter(record => record.day_id === dayId)

            return state.dishes
                .map(dish => {
                    dish.count = dayDishPupilRecords.some(record => record.dish_id === dish.id) ?
                    dayDishPupilRecords.filter(record => record.dish_id === dish.id).length : 0
                    dish.price = dayDishPriceRecords.some(record => record.dish_id === dish.id) ?
                        dayDishPriceRecords.find(record => record.dish_id === dish.id).price : 0
                    return dish
                } )
                .sort((a, b) => a.order_id - b.order_id)
        },

        pupilAbsence: (state) => (pupilId, dayId) => {
            const absence = state.absences.find(record =>
                record.day_id === dayId
                && record.pupil_id === pupilId
            )
            return absence === undefined ? false : absence
        },

        pupilInitialBalance: (state) => (pupilId) => {
            const initialBalance = state.initialBalances.find(balance => balance.pupil_id === pupilId)
            return initialBalance === undefined ? 0 : initialBalance.initial_amount
        },

        pupilsInitialBalances (state) {
            return state.initialBalances.length !== 0
        },

        pupilWasted: (state, getters) => (pupilId) => {
            return state.dayDishPupil
                .filter(record => record.pupil_id === pupilId)
                .reduce((sum, current) => {
                    return sum + getters.dishPrice(current.day_id, current.dish_id)
                }, 0)
        },

        pupilsWasteds (state, getters) {
            return state.dayDishPupil.reduce((sum, current) => {
                return sum + getters.dishPrice(current.day_id, current.dish_id)
            }, 0)
        },

        pupilBalance: (state, getters) => (pupilId) => {
            return getters.pupilInitialBalance(pupilId) + getters.pupilPayment(pupilId) - getters.pupilWasted(pupilId)
            // + pupil.paid in the future
        },

        pupilsDebts (state, getters) {
            return state.pupils.reduce((sum, current) => {
                if (getters.pupilBalance(current.id) < 0) {
                    return sum + getters.pupilBalance(current.id)
                }
                return sum
            }, 0)
        },
        
        
        pupilsSurpluses (state, getters) {
            return state.pupils.reduce((sum, current) => {
                if (getters.pupilBalance(current.id) > 0) {
                    return sum + getters.pupilBalance(current.id)
                }
                return sum
            }, 0)
        },

        pupilPayment: (state) => (pupilId) => {
            const pupilPayment = state.payments.find(payment => payment.pupil_id === pupilId)
            return pupilPayment === undefined ? 0 : pupilPayment.amount
        },

        pupilsPayments (state) {
            return state.payments.reduce((sum, current) => {
                return sum + current.amount
            }, 0)
        },

        dish: (state) => (id) => {
            return state.dishes.filter(dish.id === id)
        },

        weeksLimits (state) {
            return state.weeksLimits
        },

        templates (state) {
            return state.templates
        },

        dishPupilTemplate (state) {
            return state.dishPupilTemplate
        },

        pupilTemplateDishes: (state) => (pupilId, templateId) => {
            return state.dishPupilTemplate
                .filter(record =>
                    record.pupil_id === pupilId
                    && record.template_id === templateId
                )
                .map(record => {
                    const dish = state.dishes.find(dish => dish.id === record.dish_id)
                    return {
                         entity: dish,
                         id: record.id
                     }
                })
        },
    }
})