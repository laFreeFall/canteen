<template>
    <b-list-group>
        <b-list-group-item v-for="(pupil, index) in pupils" :key="pupil.id" class="align-middle">
            <b-badge>{{ (index + 1) }}</b-badge>
            <icon :name="pupil.gender ? 'male' : 'female'"></icon>
            {{ `${pupil.last_name} ${pupil.first_name}` }}
            <b-button-group size="sm" class="float-right">
                <b-button
                    variant="info"
                    v-b-tooltip.hover
                    title="Изменить информацию"
                    @click="$emit('editButtonWasClicked', pupil)"
                >
                    <icon name="edit"></icon>
                </b-button>
                <b-button
                    variant="danger"
                    v-b-tooltip.hover
                    :title="pupil.deleted_at === null ? 'Скрыть ученика' : 'Восстановить ученика'"
                    @click="$emit('hideButtonWasClicked', pupil)"
                >
                    <icon :name="pupil.deleted_at === null ? 'eye' : 'eye-slash'"></icon>
                </b-button>
            </b-button-group>
        </b-list-group-item>
    </b-list-group>
</template>

<script>
    export default {
        props: {
            pupils: {
                type: Array,
                required: true
            }
        }
    }
</script>
