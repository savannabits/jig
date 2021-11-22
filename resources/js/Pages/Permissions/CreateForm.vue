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
            <inertia-button type="submit" class="font-semibold bg-success disabled:opacity-25" :disabled="form.processing">Submit</inertia-button>
        </div>
    </form>
</template>

<script>
    import JetInput from "@/Jetstream/Input.vue";
    import JetLabel from "@/Jetstream/Label.vue";
    import InertiaButton from "@/JigComponents/InertiaButton.vue";
    import JetInputError from "@/Jetstream/InputError.vue"
    import {useForm} from "@inertiajs/inertia-vue3";
    export default {
        name: "CreatePermissionsForm",
        components: {
            InertiaButton,
            JetInputError,
            JetLabel,
                         JetInput,                                    
        },
        data() {
            return {
                form: useForm({
                    name: null,
                    title: null,
                    guard_name: null,
                                                            
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
                this.form.post(this.route('admin.permissions.store'),{
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
