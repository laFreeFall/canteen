<template>
    <button
        class="btn btn-outline-dark btn-sm dish-btn"
        v-tooltip="`${dish.title}: ${dish.price / 100}&#8372;`"
        @click="remove"
    >
        {{ dish.abbr }}
    </button>
</template>

<script>
export default {
    props: {
        dish: {
            type: Object,
            required: true
        },
        pupilId: {
            type: Integer,
            required: true
        }
    },

    methods: {
        remove () {
            console.log('trying to remive dish ' + this.dish.abbr + ' from pupil ' + this.pupilId)
        }
    },

    mounted () {
        // console.log('I am mounted and I belong to ' + this.dish.pupil_id + ' user')
        // console.log(this.dish)
    }
}
</script>
