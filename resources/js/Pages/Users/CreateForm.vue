<template>
    <form class="w-full" @submit.prevent="storeModel">
        
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
            <jet-input type="password" id="password" name="password" v-model="form.password"
                       :class="{'border-red-500 sm:focus:border-red-300 sm:focus:ring-red-100': form.errors.password}"
            ></jet-input>
            <jet-input-error :message="form.errors.password" class="mt-2" />
        </div>
        <div class=" sm:col-span-4">
            <jet-label for="password_confirmation" value="Repeat Password" />
            <jet-input type="password" id="password_confirmation" name="password_confirmation" v-model="form.password_confirmation"
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
            <inertia-button type="submit" class="font-semibold bg-success disabled:opacity-25" :disabled="form.processing">Submit</inertia-button>
        </div>
    </form>
</template>

<script>
    import JigDatepicker from "@/JigComponents/JigDatepicker.vue";
    import JetInput from "@/Jetstream/Input.vue";
    import JigTextarea from "@/JigComponents/JigTextarea.vue";
    import JetLabel from "@/Jetstream/Label.vue";
    import InertiaButton from "@/JigComponents/InertiaButton.vue";
    import JetInputError from "@/Jetstream/InputError.vue"
    import {useForm} from "@inertiajs/inertia-vue3";
    export default {
        name: "CreateUsersForm",
        components: {
            InertiaButton,
            JetInputError,
            JetLabel,
            JigDatepicker,
            JetInput,
            JigTextarea,
        },
        data() {
            return {
                form: useForm({
                    name: null,
                    email: null,
                    password: null,
                    password_confirmation: null,
                    profile_photo_path: null,
                    two_factor_secret: null,
                    two_factor_recovery_codes: null,
                    email_verified_at: null,
                    current_team_id: null,                                    
                }, {remember: false}),
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
            async storeModel() {
                this.form.post(this.route('admin.users.store'),{
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
            }
        }
    }
</script>

<style scoped>

</style>
