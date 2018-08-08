<template>
    <div>
        <div class="row justify-content-center">
            <div class="col-md-4">
                <b-card>
                    <h5 slot="header" class="text-center">
                        Активные блюда
                        <b-badge pill variant="primary">{{ activeDishes.length }}</b-badge>
                    </h5 >
                    <div class="text-center">
                        <b-button variant="primary" @click="$refs.createDishModal.show()">Добавить блюдо</b-button>
                    </div>
                    <hr>
                    <dishes-list
                        :dishes="activeDishes"
                        @editButtonWasClicked="showEditDishModal"
                        @hideButtonWasClicked="showHideDishModal"
                    ></dishes-list>
                </b-card>

                <b-card v-if="inactiveDishes.length">
                    <h5 slot="header" class="text-center">
                        Неактивные блюда
                        <b-badge pill variant="primary">{{ inactiveDishes.length }}</b-badge>
                    </h5>
                    <dishes-list
                        :dishes="inactiveDishes"
                        @editButtonWasClicked="showEditDishModal"
                        @hideButtonWasClicked="showHideDishModal"
                    ></dishes-list>
                </b-card>

            </div>
        </div>

        <!-- Modal for creating new dish -->
        <b-modal
            ref="createDishModal"
            hide-footer
            :title="modalState === 'create' ? 'Добавление нового блюда': 'Изменение информации о блюде'"
            @hidden="onHidden"
        >
            <b-form>
                <b-form-group label="Название">
                    <b-form-input
                        placeholder="Введите название"
                        v-model.trim="formData.title"
                        aria-describedby="inputTitleHelp"
                        required
                    >
                    </b-form-input>
                    <b-form-text id="inputTitleHelp">
                        Полное название блюда (будет отображаться во всплывающих подсказках)
                    </b-form-text>
                </b-form-group>
                <b-form-group label="Аббревиатура">
                    <b-form-input
                        placeholder="Введите аббревиатуру"
                        v-model.trim="formData.abbr"
                        aria-describedby="inputAbbrHelp"
                        required
                    >
                    </b-form-input>
                    <b-form-text id="inputAbbrHelp">
                        Абрревиатура блюда, состоящая из 1 буквы (отображается в таблице отчёта)
                    </b-form-text>
                </b-form-group>
                <b-form-group label="Иконка">
                    <b-input-group>
                        <b-form-input
                            placeholder="Введите название иконки"
                            v-model.trim="formData.icon"
                            aria-describedby="inputIconHelp"
                            required
                        >
                        </b-form-input>
                        <b-input-group-append>
                            <b-btn variant="outline-secondary">
                                <icon :name="formData.icon"></icon>
                            </b-btn>
                        </b-input-group-append>
                    </b-input-group>

                    <b-form-text id="inputIconHelp">
                        Название иконки, отображающейся в окне при добавлении блюда 
                        (просмотреть полный список доступных иконок и получить название желаемой можно <a href="https://fontawesome.com/icons?d=gallery&m=free" target="_blank">здесь</a>).<br>
                        <strong>Если при изменении названия иконки она не отобразилась справа, значит, она недоступна. Попробуйте выбрать другую.</strong>
                    </b-form-text>
                </b-form-group>
                <b-form-group label="Действие">
                    <b-input-group :append="`Ученик ${actionForForm}`">
                        <b-form-input
                            placeholder="Введите действие с блюдом"
                            v-model.trim="formData.action"
                            aria-describedby="inputActionHelp"
                            required
                        >
                        </b-form-input>
                    </b-input-group>

                    <b-form-text id="inputActionHelp">
                        Название действия, которое ученик совершает с блюдом
                        (необходимо для отображения во всплывающих уведомлениях)
                    </b-form-text>
                </b-form-group>
                <b-form-group label="Родительный падеж">
                    <b-input-group :append="`Ученик ${accusativeForForm}`">
                        <b-form-input
                            placeholder="Название блюда в родительном падеже"
                            v-model.trim="formData.accusative"
                            aria-describedby="inputAccusativeHelp"
                            required
                        >
                        </b-form-input>
                    </b-input-group>
                    <b-form-text id="inputAccusativeHelp">
                        Полное название блюда в родительном падеже
                        (необходимо для отображения во всплывающих уведомлениях)
                    </b-form-text>
                </b-form-group>
            </b-form>
            <b-btn
                v-show="modalState === 'create'"
                variant="outline-primary"
                @click="createDish"
                class="float-left"
                :disabled="!submitButtonEnabled"
            >
                Добавить блюдо
            </b-btn>
            <b-btn
                v-show="modalState === 'edit'"
                variant="outline-primary"
                @click="editDish"
                class="float-left"
                :disabled="!submitButtonEnabled"
            >
                Сохранить изменения
            </b-btn>
            <b-btn
                variant="outline-danger"
                @click="$refs.createDishModal.hide()"
                class="float-right"
            >
                Отменить
            </b-btn>
        </b-modal>


        <!-- Modal for hiding existing dish -->
        <b-modal
            ref="hideDishModal"
            hide-footer
            title="Скрытие блюда"
            @hidden="onHidden"
        >
            <b-alert :show="selectedDish.deleted_at === null" variant="warning">
                Вы уверены, что хотите скрыть блюдо {{ selectedDish.title }} ({{ selectedDish.abbr }})?
                <br>
                После выполнения этого действия блюдо не будет отображаться в последующих неделях.
                <br>
                Вы можете восстановить его в дальнейшем из списка неактивных блюд снизу.
            </b-alert>
            <b-alert :show="selectedDish.deleted_at !== null" variant="warning">
                Вы уверены, что хотите восстановить блюдо {{ selectedDish.title }} ({{ selectedDish.abbr }})?
                <br>
                После выполнения этого действия блюдо будет отображаться в последующих неделях.
            </b-alert>
            <b-btn
                variant="outline-primary"
                @click="hideDish"
                class="float-left"
            >
                {{ selectedDish.deleted_at === null ? 'Скрыть блюдо' : 'Восстановить блюдо' }}
            </b-btn>
            <b-btn
                variant="outline-danger"
                @click="$refs.hideDishModal.hide()"
                class="float-right"
            >
                Отменить
            </b-btn>
        </b-modal>

    </div>

</template>

<script>
    import { mapGetters, mapActions } from 'vuex'
    import DishesList from './DishesList.vue'

    export default {
        components: {
            DishesList
        },

        data () {
            return {
                selectedDish: {
                    id: 0,
                    title: '',
                    abbr: '',
                    icon: 'cube',
                    accusative: '',
                    action: '',
                    deleted_at: null
                },

                formData: {
                    title: '',
                    abbr: '',
                    icon: 'cube',
                    accusative: '',
                    action: ''
                },
                modalState: 'create'
            }
        },

        methods: {
            ...mapActions([
                'addDish',
                'updateDish',
                'deleteDish'
            ]),

            setFormData (dish) {
                this.resetFormData()
                this.setSelectedDish(dish)
                this.formData.title = dish.title
                this.formData.abbr = dish.abbr
                this.formData.icon = dish.icon
                this.formData.accusative = dish.accusative
                this.formData.action = dish.action
            },

            resetFormData () {
                this.resetSelectedDish()
                this.formData.title = ''
                this.formData.abbr = ''
                this.formData.icon = 'cube'
                this.formData.accusative = ''
                this.formData.action = ''
            },

            setSelectedDish (dish) {
                this.selectedDish.id = dish.id
                this.selectedDish.title = dish.title
                this.selectedDish.abbr = dish.abbr
                this.selectedDish.icon = dish.icon
                this.selectedDish.accusative = dish.accusative
                this.selectedDish.action = dish.action
                this.selectedDish.deleted_at = dish.deleted_at
            },

            resetSelectedDish () {
                this.selectedDish.id = 0
                this.selectedDish.title = ''
                this.selectedDish.abbr = ''
                this.selectedDish.icon = 'cube'
                this.selectedDish.accusative = ''
                this.selectedDish.action = ''
                this.selectedDish.deleted_at = null
            },

            createDish () {
                this.addDish({
                    title: this.formData.title,
                    abbr: this.formData.abbr,
                    icon: this.formData.icon,
                    accusative: this.formData.accusative,
                    action: this.formData.action
                })
                    .then(response => {
                        this.$refs.createDishModal.hide()
                        this.resetFormData()
                        this.$snotify.success('Блюдо было успешно добавлено!', '')
                    })
                    .catch(error => {
                        this.$snotify.error('При добавлении блюда произошла ошибка.', 'Ошибка')
                    })
            },

            showEditDishModal (dish) {
                this.setFormData(dish)
                this.modalState = 'edit'
                this.$refs.createDishModal.show()
            },

            editDish () {
                this.updateDish({
                    id: this.selectedDish.id,
                    title: this.formData.title,
                    abbr: this.formData.abbr,
                    icon: this.formData.icon,
                    accusative: this.formData.accusative,
                    action: this.formData.action
                })
                    .then(response => {
                        this.$refs.createDishModal.hide()
                        this.$snotify.success('Информация о блюде была успешно изменена!', '')
                        this.resetFormData()
                    })
                    .catch(error => {
                        this.$snotify.error('При изменении информации о блюде произошла ошибка.', 'Ошибка')
                    })
            },

            showHideDishModal (dish) {
                this.setFormData(dish)
                this.$refs.hideDishModal.show()
            },

            hideDish () {
                const listName = this.selectedDish.deleted_at !== null ? 'активных' : 'неактивных'
                this.deleteDish({ id: this.selectedDish.id })
                    .then(response => {
                        this.$refs.hideDishModal.hide()
                        this.resetFormData()
                        this.$snotify.success(`Блюдо было успешно перемещёно в список ${listName}!`, '')
                    })
                    .catch(error => {
                        this.$snotify.error(`При перемещении блюда в список ${listName} произошла ошибка.`, 'Ошибка')
                    })
            },

            onHidden () {
                this.resetFormData()
            }
        },

        computed: {
            ...mapGetters([
                'dishes'
            ]),

            activeDishes () {
                return this.dishes.filter(dish => ! dish.deleted_at)
            },

            inactiveDishes () {
                return this.dishes.filter(dish => dish.deleted_at)
            },
            
            submitButtonEnabled () {
                return this.formData.title.length > 2 &&
                    this.formData.abbr.length < 3 &&
                    this.formData.icon.length > 2 &&
                    this.formData.accusative.length > 2 &&
                    this.formData.action.length > 2
            },

            accusativeForForm () {
                const action = this.formData.action ? this.formData.action : 'употребил'
                return this.formData.accusative ? action + ' ' + this.formData.accusative : action + '...'
            },

            actionForForm () {
                const action = this.formData.action ? this.formData.action : '...'
                let dish = 'блюдо'
                dish = this.formData.title ? this.formData.title.toLowerCase() : dish
                dish = this.formData.accusative ? this.formData.accusative : dish
                return action + ' ' + dish
            }
        }
    }
</script>

<style scoped>
    .fa-icon {
        width: auto;
        height: 0.9em;
    }
    .badge-secondary {
        margin-right: 1em;
    }
</style>
