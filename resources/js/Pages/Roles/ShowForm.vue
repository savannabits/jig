<template>
    <dl class="gap-4">
        <jig-dd>
            <template #dt>Name:</template>
            {{ model.name }}
        </jig-dd>
        <jig-dd>
            <template #dt>Title:</template>
            {{ model.title }}
        </jig-dd>
        <jig-dd>
            <template #dt>Guard Name:</template>
            {{ model.guard_name }}
        </jig-dd>
    </dl>
</template>

<script>
    import JigDd from "@/JigComponents/JigDd.vue";
    import InertiaButton from "@/JigComponents/InertiaButton.vue";

    export default {
        name: "ShowRolesForm",
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
