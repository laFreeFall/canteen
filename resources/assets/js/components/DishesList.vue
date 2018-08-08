<template>

    <b-list-group>
        <draggable @end="onUpdate" :options="{ disabled: dishes[0].deleted_at !== null }">
            <b-list-group-item
                v-for="(dish, index) in dishes"
                :key="dish.id"
                class="align-middle"
                :style="{ cursor: dish.deleted_at === null ? 'move' : 'default' }"
            >
                <b-badge>{{ (index + 1) }}</b-badge>
                <icon :name="dish.icon"></icon>
                {{ dish.title }}
                <b-button-group size="sm" class="float-right">
                    <b-button
                        variant="info"
                        v-b-tooltip.hover
                        title="Изменить информацию"
                        @click="$emit('editButtonWasClicked', dish)"
                    >
                        <icon name="edit"></icon>
                    </b-button>
                    <b-button
                        variant="danger"
                        v-b-tooltip.hover
                        :title="dish.deleted_at === null ? 'Скрыть блюдо' : 'Восстановить блюдо'"
                        @click="$emit('hideButtonWasClicked', dish)"
                    >
                        <icon :name="dish.deleted_at === null ? 'eye' : 'eye-slash'"></icon>
                    </b-button>
                </b-button-group>
            </b-list-group-item>
        </draggable>

    </b-list-group>
</template>

<script>
    import { mapActions } from 'vuex'
    import draggable from 'vuedraggable'

    export default {
        props: {
            dishes: {
                type: Array,
                required: true
            }
        },

        components: {
            draggable
        },

        methods: {
            ...mapActions([
                'swapDishes'
            ]),

            onUpdate (event) {
                if(event.oldIndex !== event.newIndex) {
                    this.swapDishes({
                        from: event.oldIndex + 1,
                        to: event.newIndex + 1
                    })
                        .then(response => {
                            this.$snotify.success('Блюда были успешно перемещены в списке!', '')
                        })
                        .catch(error => {
                            this.$snotify.error('При перемещении блюд в списке произошла ошибка.', 'Ошибка')
                        })
                } else {
                    this.$snotify.warning(`Блюда остались на своих местах!`, '')
                }
            }
        }
    }
</script>
