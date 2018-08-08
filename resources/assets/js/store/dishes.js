export default {
    mutations: {
        ADD_DAY_DISH_PUPIL (state, dayDishPupilEntity) {
            state.dayDishPupil.push(dayDishPupilEntity)
            // add wasted remove balance
        },

        DELETE_DAY_DISH_PUPIL (state, dayDishPupilEntity) {
            state.dayDishPupil.splice(state.dayDishPupil.findIndex(record =>
                record.day_id === dayDishPupilEntity.day_id &&
                record.dish_id === dayDishPupilEntity.dish_id &&
                record.pupil_id === dayDishPupilEntity.pupil_id
            ), 1)
            // remove wasted add balance
        },
    },

    actions: {
        addDayDishPupil (context, dayDishPupilEntity) {
            return axios.post(`/pupils/${dayDishPupilEntity.pupil_id}/dishes/${dayDishPupilEntity.dish_id}/days/${dayDishPupilEntity.day_id}`)
                .then(response => {
                    context.commit('ADD_DAY_DISH_PUPIL', response.data, { root: true })
                    return response.data
                })
                .catch(error => {
                    console.log(error)
                    reject(error)
                })
        },
        
        deleteDayDishPupil (context, dayDishPupilEntity) {
            return axios.delete(`/pupils/${dayDishPupilEntity.pupil_id}/dishes/${dayDishPupilEntity.dish_id}/days/${dayDishPupilEntity.day_id}`)
            .then(response => {
                context.commit('DELETE_DAY_DISH_PUPIL', dayDishPupilEntity, { root: true })
                return response.data
            })
            .catch(error => {
                console.log(error)
                reject(error)
            })
        },
    },

    getters: {
        pupilDishes: (state, getters, rootState, rootGetters) => (pupilId, dayId) => {
            return rootState.dayDishPupil
                .filter(record =>
                    record.day_id === dayId
                    && record.pupil_id === pupilId
                )
                .map(record => { 
                    const dish = rootState.dishes.find(dish =>
                        dish.id === record.dish_id
                    )
                    dish.price = rootGetters.dishPrice(dayId, dish.id)
                    return dish
                })
                .sort((a, b) => a.order_id - b.order_id)
        },

        dayTotalDishes: (state, getters, rootState, rootGetters) => (dayId) => {
            const dayDishPupilRecords = rootState.dayDishPupil.filter(record => record.day_id === dayId)
            const dayDishPriceRecords = rootState.dayDishPrice.filter(record => record.day_id === dayId)

            return rootState.dishes
                .map(dish => {
                    dish.count = dayDishPupilRecords.some(record => record.dish_id === dish.id) ?
                    dayDishPupilRecords.filter(record => record.dish_id === dish.id).length : 0
                    dish.price = dayDishPriceRecords.some(record => record.dish_id === dish.id) ?
                        dayDishPriceRecords.find(record => record.dish_id === dish.id).price : 0
                    return dish
                } )
                .sort((a, b) => a.order_id - b.order_id)
        },

    }
}