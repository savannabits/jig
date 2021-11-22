<template>
    <dl class="gap-4">
        <jig-dd>
            <template #dt>Name:</template>
            {{ model.name }}
        </jig-dd>
        <jig-dd>
            <template #dt>Email:</template>
            {{ model.email }}
        </jig-dd>
        <jig-dd>
            <template #dt>Password:</template>
            {{ model.password }}
        </jig-dd>
        <jig-dd>
            <template #dt>Profile Photo Path:</template>
            {{ model.profile_photo_path }}
        </jig-dd>
        <jig-dd>
            <template #dt>Two Factor Secret:</template>
            {{ model.two_factor_secret }}
        </jig-dd>
        <jig-dd>
            <template #dt>Two Factor Recovery Codes:</template>
            {{ model.two_factor_recovery_codes }}
        </jig-dd>
        <jig-dd>
            <template #dt>Email Verified At:</template>
            {{ model.email_verified_at }}
        </jig-dd>
        <jig-dd>
            <template #dt>Current Team Id:</template>
            {{ model.current_team_id }}
        </jig-dd>
    </dl>
</template>

<script>
    import JigDd from "@/JigComponents/JigDd.vue";
    import InertiaButton from "@/JigComponents/InertiaButton.vue";

    export default {
        name: "ShowUsersForm",
        props: {
            model: Object,
        },
        components: {
            InertiaButton,
            JigDd,
        },
        data() {
            return {}
        },
        mounted() {},
        computed: {
            flash() {
                return this.$page.props.flash || {}
            }
        },
        methods: {}
    }
</script>

<style scoped>

</style>
