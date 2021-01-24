<template>
    <jet-dialog-modal :show="bookingRequest" @close="closeModal">
        <template #title>
            Booking Request Status
        </template>

        <template #content>
            <div v-if="bookingRequest">
                <div class="overflow-y-auto max-h-96 h-48">
                    <div v-for="log in bookingRequest.log" class="text-md mx-3">
                        <div>{{log}}</div>
                    </div>
                </div>

                <div class="max-w-xl mx-auto my-4 pb-4">	
                    <div class="flex pb-3">
                        <div class="flex-1">
                        </div>

                        <!-- Request sent -->
                        <div v-if="bookingRequest.status" class="flex-1">
                            <div class="w-10 h-10 bg-green-500  mx-auto rounded-full">
                            </div>
                        </div>
                        <div v-else class="flex-1">
                            <div class="w-10 h-10 border-2 mx-auto rounded-full">
                            </div>
                        </div>
                        
                        <div v-if="bookingRequest.status" class="w-1/5 align-center items-center align-middle content-center flex">
                            <div class="w-full bg-green-500 rounded items-center align-middle align-center flex-1 z-10">
                                <div class="bg-green-500 text-xs leading-none py-1 rounded "></div>
                            </div>
                        </div> 
                        <div v-else class="w-1/5 align-center items-center align-middle content-center flex">
                            <div class="w-full bg-grey-light rounded items-center align-middle align-center flex-1">
                                <div class="bg-gray-200 text-xs leading-none py-1 text-center text-grey-darkest rounded"></div>
                            </div>
                        </div>           

                        <!-- Under Review -->
                        <div v-if="bookingRequest.status == 'review'||bookingRequest.status == 'approved'" class="flex-1">
                            <div class="w-10 h-10 bg-green-500  mx-auto rounded-full">
                            </div>
                        </div>
                        <div v-else class="flex-1">
                            <div class="w-10 h-10 border-2 mx-auto rounded-full">
                            </div>
                        </div>

                        <div v-if="bookingRequest.status == 'approved'" class="w-1/5 align-center items-center align-middle content-center flex">
                            <div class="w-full bg-green-500 rounded items-center align-middle align-center flex-1 z-10">
                                <div class="bg-green-500 text-xs leading-none py-1 rounded "></div>
                            </div>
                        </div> 
                        <div v-else class="w-1/5 align-center items-center align-middle content-center flex">
                            <div class="w-full bg-grey-light rounded items-center align-middle align-center flex-1">
                                <div class="bg-gray-200 text-xs leading-none py-1 text-center text-grey-darkest rounded"></div>
                            </div>
                        </div>
                        
                        <!-- Approved -->
                        <div v-if="bookingRequest.status == 'approved'" class="flex-1">
                            <div class="w-10 h-10 bg-green-500  mx-auto rounded-full">
                            </div>
                        </div>
                        <div v-else class="flex-1">
                            <div class="w-10 h-10 border-2 mx-auto rounded-full">
                            </div>
                        </div>
                                        
                        <div class="flex-1">
                        </div>		
                    </div>
                    
                    <div class="flex text-xs content-center text-center">
                        <div class="w-1/3">
                            Request Sent
                        </div>
                        
                        <div class="w-1/3">
                            Under Review
                        </div>
                        
                        <div class="w-1/3">
                            Approved
                        </div>			
                    </div>
                </div>
            </div>
        </template>

        <template #footer>
            <jet-button @click.native="closeModal">
                Done
            </jet-button>
        </template>
    </jet-dialog-modal>
</template>

<script>
import JetActionMessage from "@src/Jetstream/ActionMessage";
import JetFormSection from "@src/Jetstream/FormSection";
import JetDialogModal from "@src/Jetstream/DialogModal";
import JetButton from "@src/Jetstream/Button";
import JetSecondaryButton from "@src/Jetstream/SecondaryButton";
import JetLabel from "@src/Jetstream/Label";
import JetInput from "@src/Jetstream/Input";
import JetInputError from "@src/Jetstream/InputError";

export default {
    components: {
        JetActionMessage,
        JetFormSection,
        JetDialogModal,
        JetButton,
        JetLabel,
        JetInput,
        JetInputError
    },

    props: {
        bookingRequest: {
            type: Object,
            required: false
        },
    },

     methods: {
        closeModal() {
            this.$emit("close");
        },
     },
};
</script>
