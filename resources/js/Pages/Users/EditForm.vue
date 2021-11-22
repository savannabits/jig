<template>
    <jig-tabs :class="`border-none`" nav-classes="bg-secondary-300 rounded-t-lg border-b-4 border-primary">
        <template #nav>
            <jig-tab-link @activate="activeTab=$event" :active-classes="tabActiveClasses" :tab-controller="activeTab"
                          tab="basic-info">Basic Info
            </jig-tab-link>
            <jig-tab-link @activate="activeTab=$event" :active-classes="tabActiveClasses" :tab-controller="activeTab"
                          tab="assign-roles">Assign Roles
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
                        <jet-label for="email" value="Email" />
                        <jet-input class="w-full" type="text" id="email" name="email" v-model="form.email"
                                   :class="{'border-red-500 sm:focus:border-red-300 sm:focus:ring-red-100': form.errors.email}"
                        ></jet-input>
                        <jet-input-error :message="form.errors.email" class="mt-2" />
                    </div>
                                 <div class=" sm:col-span-4">
                        <jet-label for="password" value="Password" />
                        <jet-input class="w-full" type="password" id="password" name="password" v-model="form.password"
                                   :class="{'border-red-500 sm:focus:border-red-300 sm:focus:ring-red-100': form.errors.password}"
                        ></jet-input>
                        <jet-input-error :message="form.errors.password" class="mt-2" />
                    </div>
                    <div class=" sm:col-span-4">
                        <jet-label for="password_confirmation" value="Repeat Password" />
                        <jet-input class="w-full" type="password" id="password_confirmation" name="password_confirmation" v-model="form.password_confirmation"
                                   :class="{'border-red-500 sm:focus:border-red-300 sm:focus:ring-red-100': form.errors.password_confirmation}"
                        ></jet-input>
                    </div>
                                 <div class=" sm:col-span-4">
                        <jet-label for="profile_photo_path" value="Profile Photo Path" />
                        <jet-input class="w-full" type="text" id="profile_photo_path" name="profile_photo_path" v-model="form.profile_photo_path"
                                   :class="{'border-red-500 sm:focus:border-red-300 sm:focus:ring-red-100': form.errors.profile_photo_path}"
                        ></jet-input>
                        <jet-input-error :message="form.errors.profile_photo_path" class="mt-2" />
                    </div>
                                <div class=" sm:col-span-4">
                        <jet-label for="two_factor_secret" value="Two Factor Secret" />
                        <jig-textarea class="w-full" id="two_factor_secret" name="two_factor_secret" v-model="form.two_factor_secret"
                                      :class="{'border-red-500 sm:focus:border-red-300 sm:focus:ring-red-100': form.errors.two_factor_secret}"
                        ></jig-textarea>
                        <jet-input-error :message="form.errors.two_factor_secret" class="mt-2" />
                    </div>
                                <div class=" sm:col-span-4">
                        <jet-label for="two_factor_recovery_codes" value="Two Factor Recovery Codes" />
                        <jig-textarea class="w-full" id="two_factor_recovery_codes" name="two_factor_recovery_codes" v-model="form.two_factor_recovery_codes"
                                      :class="{'border-red-500 sm:focus:border-red-300 sm:focus:ring-red-100': form.errors.two_factor_recovery_codes}"
                        ></jig-textarea>
                        <jet-input-error :message="form.errors.two_factor_recovery_codes" class="mt-2" />
                    </div>
                                <div class=" sm:col-span-4">
                        <jet-label for="email_verified_at" value="Email Verified At" />
                        <jig-datepicker class="w-full"
                                        data-date-format="Y-m-d H:i:s"
                                        :data-alt-input="true"
                                        data-alt-format="l M J, Y at h:i K"
                                        data-default-hour="9"
                                        :data-enable-time="true"
                                        name="email_verified_at"
                                        v-model="form.email_verified_at"
                                        id="email_verified_at"
                                        :class="{'border-red-500 sm:focus:border-red-300 sm:focus:ring-red-100': form.errors.email_verified_at}"
                        ></jig-datepicker>
                        <jet-input-error :message="form.errors.email_verified_at" class="mt-2" />
                    </div>
                                 <div class=" sm:col-span-4">
                        <jet-label for="current_team_id" value="Current Team Id" />
                        <jet-input class="w-full" type="text" id="current_team_id" name="current_team_id" v-model="form.current_team_id"
                                   :class="{'border-red-500 sm:focus:border-red-300 sm:focus:ring-red-100': form.errors.current_team_id}"
                        ></jet-input>
                        <jet-input-error :message="form.errors.current_team_id" class="mt-2" />
                    </div>
                                                    
                    <div class="mt-2 text-right">
                        <inertia-button type="submit" class="bg-primary font-semibold text-white" :disabled="form.processing">Submit</inertia-button>
                    </div>
                </form>
            </jig-tab>
            <jig-tab name="assign-roles" :tab-controller="activeTab">
                <div class="my-2 border rounded-md p-3">
                    <h3 class="font-bold py-3 text-lg">Assign Roles</h3>
                    <hr>
                    <div class="p-2 mt-2 border rounded">
                        <div style="cursor: pointer" v-for="(role, idx) of form.assigned_roles" :key="idx"
                             class=" sm:col-span-4 px-10 flex border-b border-gray-100 justify-between py-3 items-center my-2">
                            <jet-label :for="role.name" class="inline-block  font-black text-xl"
                                       :value="role.title"/>
                            <jet-checkbox @change="toggleRole(role)" class="p-2 inline-block" type="checkbox"
                                          :id="role.name" :name="role.name" :checked="role.checked" v-model="role.checked"
                                          :class="{'border-red-500 sm:focus:border-red-300 sm:focus:ring-red-100': form.errors.assigned_roles}"
                            ></jet-checkbox>
                        </div>
                    </div>
                </div>
            </jig-tab>
        </template>
    </jig-tabs>
</template>

<script>
    import JetLabel from "@/Jetstream/Label.vue";
    import InertiaButton from "@/JigComponents/InertiaButton.vue";
    import JetInputError from "@/Jetstream/InputError.vue";
    import {useForm} from "@inertiajs/inertia-vue3";
    import JigTab from "@/JigComponents/JigTab.vue";
    import JigTabs from "@/JigComponents/JigTabs.vue";
    import JigTabLink from "@/JigComponents/JigTabLink.vue";

    import JetCheckbox from "@/Jetstream/Checkbox.vue";
import JigDatepicker from "@/JigComponents/JigDatepicker.vue";
    import JetInput from "@/Jetstream/Input.vue";
    import JigTextarea from "@/JigComponents/JigTextarea.vue";
    
    export default {
        name: "EditUsersForm",
        props: {
            model: Object,
            roles: Object,
        },
        components: {
            InertiaButton,
            JetLabel,
            JetInputError,
            JetInput,
            JigDatepicker,
            JetCheckbox,
            JigTextarea,
            
            JigTabLink,
            JigTabs,
            JigTab,
        },
        data() {
            return {
                form: useForm({
                    ...this.model,
                    assigned_roles: this.roles,
                    "password_confirmation": null,
                },{remember:false}),
                activeTab: 'basic-info',
                tabActiveClasses: "bg-primary font-bold text-secondary rounded-t-lg hover:bg-primary-500"
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
                this.form.put(this.route('admin.users.update',this.form.id),
                    {
                        onSuccess: res => {
                            if (this.flash.success) {
                                this.$emit('success',this.flash.success);
                            } else if (this.flash.error) {
                                this.$emit('error',this.flash.error);
                            } else {
                                this.$emit('error',"Unknown server error.")
                            }
                        },
                        onError: res => {
                            this.$emit('error',"A server error occurred");
                        }
                    },{remember: false, preserveState: true})
            },
            async toggleRole(role) {
                const vm = this;
                axios.post(this.route('api.users.assign-role',this.form.id),{role: role}).then(res => {
                    this.$inertia.reload({preserveState: true});
                    this.displayNotification('success',res.data.message)
                }).catch(err => {
                    this.displayNotification('error',err.response?.data?.message || err.message || err)
                    vm.form.assigned_roles[role.name].checked = !role.checked;
                });
            }
        }
    }
</script>

<style scoped>

</style>
