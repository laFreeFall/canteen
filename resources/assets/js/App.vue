<template>
    <div>
        <app-header></app-header>
        <b-container fluid>
            <transition name="fade">
                <router-view></router-view>
            </transition>
            <vue-snotify></vue-snotify>
        </b-container>
    </div>
</template>

<script>
    import { mapGetters, mapActions } from 'vuex'
    import Header from './components/Header.vue'

    export default {
        components: {
            'app-header': Header
        },

        computed: {
            ...mapGetters([
                'loaded'
            ]),
        },

        methods: {
            ...mapActions([
                'loadInitialState'
            ]),
        },

        created () {
            if (! this.loaded) {
                this.loadInitialState(window.__INITIAL_STATE__)
            }
        }
    }
</script>

<style>
    .fade-enter-active, .fade-leave-active {
    transition-property: opacity;
    transition-duration: .15s;
    }

    .fade-enter-active {
    transition-delay: .15s;
    }

    .fade-enter, .fade-leave-active {
    opacity: 0
    }
</style>
