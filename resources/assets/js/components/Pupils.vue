<template>
    <div>
        <div class="row justify-content-center">
            <div class="col-md-4">
                <b-card>
                    <h5 slot="header" class="text-center">
                        Активные ученики
                        <b-badge pill variant="primary">{{ activePupils.length }}</b-badge>
                    </h5 >
                    <div class="text-center">
                        <b-button variant="primary" @click="showCreatePupilModal()">Добавить ученика</b-button>
                    </div>
                    <hr>
                    <pupils-list
                        :pupils="activePupils"
                        @editButtonWasClicked="showEditPupilModal"
                        @hideButtonWasClicked="showHidePupilModal"
                    ></pupils-list>
                </b-card>

                <b-card v-if="inactivePupils.length">
                    <h5 slot="header" class="text-center">
                        Неактивные ученики
                        <b-badge pill variant="primary">{{ inactivePupils.length }}</b-badge>
                    </h5>
                    <pupils-list
                        :pupils="inactivePupils"
                        @editButtonWasClicked="showEditPupilModal"
                        @hideButtonWasClicked="showHidePupilModal"
                    ></pupils-list>
                </b-card>

            </div>
        </div>

        <!-- Modal for creating new pupil -->
        <b-modal
            ref="createPupilModal"
            hide-footer
            :title="modalState === 'create' ? 'Добавление нового ученика' : 'Редактирование ученика'"
            @hidden="onHidden"
        >
            <b-form>
                <b-form-group label="Фамилия">
                    <b-form-input
                        placeholder="Введите фамилию"
                        v-model.trim="formData.last_name"
                        required
                    >
                    </b-form-input>
                </b-form-group>
                <b-form-group label="Имя">
                    <b-form-input
                        placeholder="Введите имя"
                        v-model.trim="formData.first_name"
                        required
                    >
                    </b-form-input>
                </b-form-group>
                <b-form-group label="Класс">
                    <b-form-select v-model="formData.grade_id" :options="gradesForForm" required>
                        <template slot="first">
                            <option :value="0" disabled>Выберите класс</option>
                        </template>
                    </b-form-select>
                </b-form-group>
                <b-form-group label="Пол">
                    <b-form-radio-group
                        buttons
                        v-model="formData.gender"
                        :options="gendersForForm"
                        button-variant="outline-secondary"
                        name="radiosBtnDefault"
                    />
                </b-form-group>
            </b-form>
            <b-btn
                v-show="modalState === 'create'"
                variant="outline-primary"
                @click="createPupil"
                class="float-left"
                :disabled="!submitButtonEnabled"
            >
                Добавить ученика
            </b-btn>
            <b-btn
                v-show="modalState === 'edit'"
                variant="outline-primary"
                @click="editPupil"
                class="float-left"
                :disabled="!submitButtonEnabled"
            >
                Обновить информацию
            </b-btn>
            <b-btn
                variant="outline-danger"
                @click="$refs.createPupilModal.hide()"
                class="float-right"
            >
                Отменить
            </b-btn>
        </b-modal>

            <!-- Modal for hiding existing pupil -->
        <b-modal
            ref="hidePupilModal"
            hide-footer
            title="Скрытие ученика"
            @hidden="onHidden"
        >
            <b-alert :show="selectedPupil.deleted_at === null" variant="warning">
                Вы уверены, что хотите скрыть ученика {{ selectedPupil.last_name }} {{ selectedPupil.first_name }}?
                <br>
                После выполнения этого действия ученик не будет отображаться в последующих неделях.
                <br>
                Вы можете восстановить его в дальнейшем из списка неактивных учеников снизу.
            </b-alert>
            <b-alert :show="selectedPupil.deleted_at !== null" variant="warning">
                Вы уверены, что хотите восстановить ученика {{ selectedPupil.last_name }} {{ selectedPupil.first_name }}?
                <br>
                После выполнения этого действия ученик будет отображаться в последующих неделях.
            </b-alert>
            <b-btn
                variant="outline-primary"
                @click="hidePupil"
                class="float-left"
            >
                {{ selectedPupil.deleted_at === null ? 'Скрыть ученика' : 'Восстановить ученика' }}
            </b-btn>
            <b-btn
                variant="outline-danger"
                @click="$refs.hidePupilModal.hide()"
                class="float-right"
            >
                Отменить
            </b-btn>
        </b-modal>

    </div>

</template>

<script>
    import { mapGetters, mapActions } from 'vuex'
    import PupilsList from './PupilsList.vue'

    export default {
        components: {
            PupilsList
        },

        data () {
            return {
                selectedPupil: {
                    id: 0,
                    first_name: '',
                    last_name: '',
                    grade_id: 1,
                    gender: true,
                    deleted_at: null
                },
                formData: {
                    first_name: '',
                    last_name: '',
                    grade_id: 1,
                    gender: true
                },
                modalState: 'create'
            }
        },

        methods: {
            ...mapActions([
                'addPupil',
                'updatePupil',
                'deletePupil'
            ]),

            setFormData (pupil) {
                this.resetFormData()
                this.setSelectedPupil(pupil)
                this.formData.first_name = pupil.first_name
                this.formData.last_name = pupil.last_name
                this.formData.grade_id = pupil.grade_id
                this.formData.gender = pupil.gender
            },

            resetFormData () {
                this.resetSelectedPupil()
                this.formData.first_name = ''
                this.formData.last_name = '',
                this.formData.grade_id = 1,
                this.formData.gender = true
            },

            setSelectedPupil (pupil) {
                this.selectedPupil.id = pupil.id
                this.selectedPupil.first_name = pupil.first_name
                this.selectedPupil.last_name = pupil.last_name
                this.selectedPupil.grade_id = pupil.grade_id
                this.selectedPupil.gender = pupil.gender
                this.selectedPupil.deleted_at = pupil.deleted_at
            },

            resetSelectedPupil () {
                this.selectedPupil.id = 0
                this.selectedPupil.first_name = ''
                this.selectedPupil.last_name = '',
                this.selectedPupil.grade_id = 1,
                this.selectedPupil.gender = true
                this.selectedPupil.deleted_at = null
            },

            showCreatePupilModal () {
                this.resetSelectedPupil()
                this.modalState = 'create'
                this.$refs.createPupilModal.show()
            },

            createPupil () {
                this.addPupil({
                    first_name: this.formData.first_name,
                    last_name: this.formData.last_name,
                    grade_id: this.formData.grade_id,
                    gender: this.formData.gender
                })
                    .then(response => {
                        this.$refs.createPupilModal.hide()
                        this.resetFormData()
                        this.$snotify.success('Ученик был успешно добавлен!', '')
                    })
                    .catch(error => {
                        this.$snotify.error('При добавлении ученика произошла ошибка.', 'Ошибка')
                    })
            },

            showEditPupilModal (pupil) {
                this.setFormData(pupil)
                this.modalState = 'edit'
                this.$refs.createPupilModal.show()
            },

            editPupil () {
                this.updatePupil({
                    id: this.selectedPupil.id,
                    first_name: this.formData.first_name,
                    last_name: this.formData.last_name,
                    grade_id: this.formData.grade_id,
                    gender: this.formData.gender
                })
                    .then(response => {
                        this.$refs.createPupilModal.hide()
                        this.resetFormData()
                        this.$snotify.success('Информация об ученике была успешно изменена!', '')
                    })
                    .catch(error => {
                        this.$snotify.error('При изменении информации об ученике произошла ошибка.', 'Ошибка')
                    })
            },

            showHidePupilModal (pupil) {
                this.setFormData(pupil)
                this.$refs.hidePupilModal.show()
            },

            hidePupil () {
                const listName = this.selectedPupil.deleted_at !== null ? 'активных' : 'неактивных'
                this.deletePupil({ id: this.selectedPupil.id })
                    .then(response => {
                        this.$refs.hidePupilModal.hide()
                        this.resetFormData()
                        this.$snotify.success(`Ученик был успешно перемещён в список ${listName}!`, '')
                    })
                    .catch(error => {
                        this.$snotify.error(`При перемещении ученика в список ${listName} произошла ошибка.`, 'Ошибка')
                    })
            },

            onHidden () {
                this.resetFormData()
            }
        },

        computed: {
            ...mapGetters([
                'pupils',
                'grades',
                'gradesForForm',
                'gendersForForm'
            ]),

            activePupils () {
                return this.pupils.filter(pupil => ! pupil.deleted_at)
            },

            inactivePupils () {
                return this.pupils.filter(pupil => pupil.deleted_at)
            },
            
            submitButtonEnabled () {
                return this.formData.first_name.length > 2 &&
                    this.formData.last_name.length > 2 &&
                    this.grade_id !== 0
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
