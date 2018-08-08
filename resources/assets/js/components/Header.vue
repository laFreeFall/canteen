<template>
    <b-navbar
        toggleable="md"
        type="dark"
        variant="primary"
    >
        <div class="d-flex w-50">
            <b-navbar-toggle target="nav_collapse"></b-navbar-toggle>
            <b-navbar-brand class="mr-0" href="/">laCanteen</b-navbar-brand>
        </div>
        <b-collapse is-nav id="nav_collapse">
            <b-navbar-nav>
                <router-link
                    :to="{
                        name: 'report-generic'
                    }"
                    active-class="active"
                    class="nav-item"
                    tag="li"
                >
                    <a href="#" class="nav-link">
                        Отчёт
                    </a>
                </router-link>

                <router-link
                    :to="{
                        name: 'calendar'
                    }"
                    active-class="active"
                    exact
                    class="nav-item"
                    tag="li"
                >
                    <a href="#" class="nav-link">
                        Календарь
                    </a>
                </router-link>

                <router-link
                    :to="{
                        name: 'pupils'
                    }"
                    active-class="active"
                    exact
                    class="nav-item"
                    tag="li"
                >
                    <a href="#" class="nav-link">
                        Ученики
                    </a>
                </router-link>

                <router-link
                    :to="{
                        name: 'dishes'
                    }"
                    active-class="active"
                    exact
                    class="nav-item"
                    tag="li"
                >
                    <a href="#" class="nav-link">
                        Блюда
                    </a>
                </router-link>

                <router-link
                    :to="{
                        name: 'templates'
                    }"
                    active-class="active"
                    exact
                    class="nav-item"
                    tag="li"
                >
                    <a href="#" class="nav-link">
                        Шаблоны
                    </a>
                </router-link>
            </b-navbar-nav>
        </b-collapse>
        <span class="navbar-text small text-truncate mt-1 w-50 text-right">
            <icon name="user"></icon>
            &nbsp; {{ username }}
        </span>
    </b-navbar>
</template>

<script>
    import 'vue-awesome/icons/user'
    import Icon from 'vue-awesome/components/Icon'
    import { mapGetters } from 'vuex'

    export default {
        components: {
            Icon
        },
        data () {
            return {
                user: {
                    firstName: 'Иван',
                    lastName: 'Иванов'
                }
            }
        },
        computed: {
            ...mapGetters([
                'currentWeek'
            ]),
            username () {
                return `${this.user.firstName} ${this.user.lastName}`
            }
        }
    }
</script>

<style>
    .navbar {
        margin-bottom: 10px;
    }
</style>
