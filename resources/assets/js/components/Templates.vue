<template>
    <b-row class="justify-content-center">
        <b-col md="12">
            <b-card>
                <h5 slot="header" class="text-center">
                    Шаблоны
                    <span @click="showCreateTemplateModal">
                        <icon
                            name="file"
                            scale="1.5"
                            class="text-primary icon-button"
                            v-tooltip="'Создать новый шаблон'"
                        >
                        </icon>
                    </span>
                </h5>
                <table class="table table-bordered text-center">
                    <thead>
                        <tr>
                            <th class="align-middle text-left template-col-pupil">
                                Ученик
                            </th>
                            <th
                                scope="col"
                                v-for="template in templates"
                                :key="template.id"
                                class="align-middle"
                            >
                                {{ template.title }}
                                <b-button
                                    variant="info"
                                    v-b-tooltip.hover
                                    title="Создать копию шаблона"
                                    size="xs"
                                    class="btn-xs-control"
                                    @click="showCloneTemplateModal(template)"
                                >
                                    <icon name="clone"></icon>
                                </b-button>
                                <b-button-group class="btn-group-xs btn-group-xs-control">
                                    <b-button
                                        variant="warning"
                                        v-b-tooltip.hover
                                        title="Изменить информацию"
                                        @click="showEditTemplateModal(template)"
                                    >
                                        <icon name="edit"></icon>
                                    </b-button>
                                    <b-button
                                        variant="danger"
                                        v-b-tooltip.hover
                                        title="Удалить шаблон"
                                        @click="showDeleteTemplateModal(template)"
                                    >
                                        <icon name="times"></icon>
                                    </b-button>
                                </b-button-group>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="pupil in pupils"
                            :key="pupil.id"
                            v-if="isPupilShown(pupil)"
                        >
                            <td class="align-middle text-left">
                                {{ `${pupil.last_name} ${pupil.first_name.charAt(0)}.` }}
                            </td>
                            <td
                                v-for="template in templates"
                                :key="template.id"
                                class="align-middle dish-btns-parent"
                            >
                                <b-button-group class="btn-group-xs">
                                    <b-button
                                        variant="outline-primary"
                                        class="dish-btn"
                                        v-for="(dish, index) in pupilTemplateDishes(pupil.id, template.id)" :key="index"
                                        v-tooltip="dish.entity.title"
                                        @click="showDeleteDishModal(dish)"
                                    >
                                        {{ dish.entity.abbr }}
                                    </b-button>
                                </b-button-group>
                                <v-popover
                                    placement="right"
                                    container=".container-fluid"
                                    class="d-inline-block"
                                >
                                    <b-button variant="outline-primary"
                                        class="btn-xs dish-btn add-dish-btn"
                                        :id="`add-dish-${pupil.id}-${template.id}`"
                                        title="Добавить блюдо"
                                        
                                    >
                                        <span class="add-dish-btn-sign">
                                            <strong>+</strong>
                                        </span>
                                    </b-button>

                                    <template slot="popover">
                                        <h6>Блюда</h6>
                                        <b-list-group>
                                            <b-list-group-item
                                                v-for="dish in dishes"
                                                :key="dish.id"
                                                @click="addDishToTemplate(dish, pupil, template)"
                                            >
                                                <icon :name="dish.icon"></icon>
                                                {{ dish.title }}
                                            </b-list-group-item>
                                        </b-list-group>
                                    </template>
                                </v-popover>
                            </td>
                        </tr>
                    </tbody>
                </table>

            </b-card>
        </b-col>

        <b-modal ref="deleteDishModal" hide-footer title="Удаление блюда">
            <b-alert show variant="warning">
                Вы действительно хотите удалить блюдо?
            </b-alert>
            <b-btn variant="outline-primary" @click="deleteDish()" class="float-left">Да, удалить</b-btn>
            <b-btn variant="outline-danger" @click="hideDeleteDishModal()" class="float-right">Отменить</b-btn>
        </b-modal>

        <b-modal
            ref="createTemplateModal"
            hide-footer
            :title="modalState === 'create' ? 'Создание шаблона' : modalState === 'clone' ? 'Клонирование шаблона' : 'Редактирование шаблона'"
        >
            <b-form>
                <b-form-group label="Название">
                    <b-form-input placeholder="Введите название шаблона" v-model.trim="formData.title"></b-form-input>
                </b-form-group>
            </b-form>
            <b-btn
                variant="outline-primary"
                v-show="modalState === 'create' || modalState === 'clone'"
                @click="createTemplate()"
                class="float-left"
            >
                Создать шаблон
            </b-btn>
            <b-btn
                variant="outline-primary"
                v-show="modalState === 'update'"
                @click="editTemplate()"
                class="float-left"
            >
                Создать шаблон
            </b-btn>
            <b-btn variant="outline-danger" @click="hideCreateTemplateModal()" class="float-right">Отменить</b-btn>
        </b-modal>

        <b-modal ref="deleteTemplateModal" hide-footer title="Удаление шаблона">
            <b-alert show variant="warning">
                Вы действительно хотите удалить шаблон?
            </b-alert>
            <b-btn variant="outline-primary" @click="deleteTemplateModal()" class="float-left">Удалить</b-btn>
            <b-btn variant="outline-danger" @click="hideDeleteTemplateModal()" class="float-right">Отменить</b-btn>
        </b-modal>

    </b-row>
</template>

<script>
    import { mapGetters, mapActions } from 'vuex'

    export default {
        data () {
            return {
                currentDishEntity: {},
                formData: {
                    id: 0,
                    title: ''
                },
                modalState: 'create'
            }
        },

        computed: {
            ...mapGetters([
                'pupils',
                'dishes',
                'templates',
                'pupilTemplateDishes',
                'isPupilShown'
            ])
        },

        methods: {
            ...mapActions([
                'addDishPupilTemplate',
                'deleteDishPupilTemplate',
                'addTemplate',
                'updateTemplate',
                'deleteTemplate',
                'cloneTemplate'
            ]),

            setFormData(template) {
                this.formData.id = template.id
                this.formData.title = template.title
            },

            resetFormData() {
                this.formData.id = 0
                this.formData.title = ''
            },

            showDeleteDishModal (dish) {
                this.currentDishEntity = dish
                this.$refs.deleteDishModal.show()
            },

            hideDeleteDishModal () {
                this.currentDishEntity = {}
                this.$refs.deleteDishModal.hide()
            },

            addDishToTemplate (dish, pupil, template) {
                this.addDishPupilTemplate({
                    'dish_id': dish.id,
                    'pupil_id': pupil.id,
                    'template_id': template.id
                })
                    .then(response => {
                        this.$refs.deleteDishModal.hide()
                        const dishAction = dish.gender ? 'был успешно добавлен' : 'была успешно добавлена'
                        this.$snotify.success(`${dish.title} ${dishAction} ученику ${pupil.last_name} ${pupil.first_name} в ${template.title}`, '')
                    })
                    .catch(error => {
                        this.$snotify.error('При добавлении блюда произошла ошибка.', 'Ошибка')
                    })
            },

            deleteDish () {
                this.deleteDishPupilTemplate({ id: this.currentDishEntity.id })
                    .then(response => {
                        this.$refs.deleteDishModal.hide()
                        this.$snotify.success('Блюдо было успешно удалено', '')
                    })
                    .catch(error => {
                        this.$snotify.error('При удалении блюда произошла ошибка.', 'Ошибка')
                    })
            },

            showEditTemplateModal (template) {
                this.setFormData(template)
                this.modalState = 'edit'
                this.$refs.createTemplateModal.show()
            },

            hideEditTemplateModal () {
                this.resetFormData()
                this.$refs.createTemplateModal.hide()
            },

            editTemplate () {
                this.updateTemplate({
                    'id': this.formData.id,
                    'title': this.formData.title
                    })
                .then(response => {
                        this.$snotify.success(`Название шаблона было успешно изменено на ${this.formData.title}!`, '')
                        this.$refs.editTemplateModal.hide()
                    })
                    .catch(error => {
                        this.$snotify.error('При изменении шаблона произошла ошибка.', 'Ошибка')
                    })
            },

            showDeleteTemplateModal (template) {
                this.setFormData(template)
                this.$refs.deleteTemplateModal.show()
            },

            hideDeleteTemplateModal () {
                this.resetFormData()
                this.$refs.deleteTemplateModal.hide()
            },

            deleteTemplateModal () {
                this.deleteTemplate({ 'id': this.formData.id })
                    .then(response => {
                        this.$snotify.success(`${this.formData.title} был успешно удалён!`, '')
                        this.$refs.deleteTemplateModal.hide()
                    })
                    .catch(error => {
                        this.$snotify.error('При удалении шаблона произошла ошибка.', 'Ошибка')
                    })
            },

            showCloneTemplateModal (template) {
                this.setFormData({
                    id: template.id,
                    title: template.title + ' (1)'
                })
                this.modalState = 'clone'
                this.$refs.createTemplateModal.show()
            },

            hideCloneTemplateModal () {
                this.resetFormData()
                this.$refs.createTemplateModal.hide()
            },

            
            cloneTemplateModal () {
                this.cloneTemplate({
                    'template_id': this.formData.id,
                    'title': this.formData.title
                })
                    .then(response => {
                        this.$snotify.success(`${this.formData.title} был успешно создан!`, '')
                        this.$refs.cloneTemplateModal.hide()
                    })
                    .catch(error => {
                        this.$snotify.error('При создании шаблона произошла ошибка.', 'Ошибка')
                    })
            },

            showCreateTemplateModal () {
                console.log('clicked')
                this.resetFormData()
                this.modalState = 'create'
                this.$refs.createTemplateModal.show()
            },

            hideCreateTemplateModal () {
                this.resetFormData()
                this.$refs.createTemplateModal.hide()
            },

            
            createTemplate () {
                this.addTemplate({ 'title': this.formData.title })
                    .then(response => {
                        this.$snotify.success(`${this.formData.title} был успешно создан!`, '')
                        this.hideCreateTemplateModal()
                    })
                    .catch(error => {
                        this.$snotify.error('При создании шаблона произошла ошибка.', 'Ошибка')
                    })
            },
        }
    }
</script>

<style scoped>
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

    .template-col-pupil {
        width: 15%;
    }

    .icon-button:hover {
        cursor: pointer;
    }
</style>
