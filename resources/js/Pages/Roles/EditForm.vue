<template>
    <jig-tabs :class="`border-none`" nav-classes="bg-secondary-300 rounded-t-lg border-b-4 border-primary">
        <template #nav>
            <jig-tab-link @activate="setTab" :active-classes="tabActiveClasses" :tab-controller="activeTab"
                          tab="basic-info">Basic Info
            </jig-tab-link>
            <jig-tab-link @activate="setTab" :active-classes="tabActiveClasses" :tab-controller="activeTab"
                          tab="assign-permissions">Assign Permissions
            </jig-tab-link>
        </template>
        <template #content>
            <jig-tab name="basic-info" :tab-controller="activeTab">
                <form @submit.prevent="updateModel">
                                     <div class=" sm:col-span-4">
            <jet-label for="name" value="Name" />
            <jet-input class="w-full" type="text" id="name" name="name" v-model="form.name"
                       :class="{'border-red-500 sm:focus:border-red-300 sm:focus:ring-red-100': form.errors.name}"
            ></jet-input>
            <jet-input-error :message="form.errors.name" class="mt-2" />
        </div>
                                     <div class=" sm:col-span-4">
            <jet-label for="title" value="Title" />
            <jet-input class="w-full" type="text" id="title" name="title" v-model="form.title"
                       :class="{'border-red-500 sm:focus:border-red-300 sm:focus:ring-red-100': form.errors.title}"
            ></jet-input>
            <jet-input-error :message="form.errors.title" class="mt-2" />
        </div>
                                     <div class=" sm:col-span-4">
            <jet-label for="guard_name" value="Guard Name" />
            <jet-input class="w-full" type="text" id="guard_name" name="guard_name" v-model="form.guard_name"
                       :class="{'border-red-500 sm:focus:border-red-300 sm:focus:ring-red-100': form.errors.guard_name}"
            ></jet-input>
            <jet-input-error :message="form.errors.guard_name" class="mt-2" />
        </div>
                                                
                    <div class="mt-2 text-right">
                        <inertia-button type="submit" class="bg-primary font-semibold text-white" :disabled="form.processing">Submit</inertia-button>
                    </div>
                </form>
            </jig-tab>
            <jig-tab name="assign-permissions" :tab-controller="activeTab">
                <assign-perms :role="model" :permissions="permissions"></assign-perms>
            </jig-tab>
        </template>
    </jig-tabs>
</template>

<script>
import JetLabel from "@/Jetstream/Label.vue";
import InertiaButton from "@/JigComponents/InertiaButton.vue";
import JetInputError from "@/Jetstream/InputError.vue";
import {useForm} from "@inertiajs/inertia-vue3";
import JetInput from "@/Jetstream/Input.vue";
import JigTabs from "@/JigComponents/JigTabs.vue";
import JigTabLink from "@/JigComponents/JigTabLink.vue";
import JigTab from "@/JigComponents/JigTab.vue";
import AssignPerms from "@/Pages/Roles/AssignPerms.vue";

export default {
    name: "EditRolesForm",
    props: {
        model: Object,
        permissions: Object,
    },
    components: {
        AssignPerms,
        JigTab,
        JigTabLink,
        JigTabs,
        InertiaButton,
        JetLabel,
        JetInputError,
        JetInput,

    },
    data() {
        return {
            form: useForm({
                ...this.model,
            }, {remember: false}),
            activeTab: 'basic-info',
            tabActiveClasses: "bg-primary font-bold text-secondary rounded-t-lg hover:bg-primary-200 hover:text-gray-800"
        }
    },
    mounted() {
    },
    computed: {
        flash() {
            return this.$page.props.flash || {}
        }
    },
    methods: {
        async updateModel() {
            this.form.put(this.route('admin.roles.update', this.form.id),
                {
                    onSuccess: res => {
                        if (this.flash.success) {
                            this.$emit('success', this.flash.success);
                        } else if (this.flash.error) {
                            this.$emit('error', this.flash.error);
                        } else {
                            this.$emit('error', "Unknown server error.")
                        }
                    },
                    onError: res => {
                        this.$emit('error', "A server error occurred");
                    }
                }, {remember: false, preserveState: true})
        },
        setTab(tab){
            this.activeTab = tab;
        }
    }
}
</script>

<style scoped>

</style>
