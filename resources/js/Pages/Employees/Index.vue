<script setup>
    import { Head, Link, useForm } from '@inertiajs/vue3';
    import { nextTick, ref } from 'vue';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import Swal from 'sweetalert2';
    import VueTailwindPagination from '@ocrv/vue-tailwind-pagination';
    
    import InputError from '@/Components/InputError.vue';
    import InputLabel from '@/Components/InputLabel.vue';
    import TextInput from '@/Components/TextInput.vue';
    import SelectInput from '@/Components/SelectInput.vue';

    import PrimaryButton from '@/Components/PrimaryButton.vue';
    import DangerButton from '@/Components/DangerButton.vue';
    import SecondaryButton from '@/Components/SecondaryButton.vue';
    import WarningButton from '@/Components/WarningButton.vue';

    import Modal from '@/Components/Modal.vue';

    const nameInput = ref(null);
    const modal = ref(false);
    const title = ref('');
    const operation = ref(1);
    const id = ref('');

    const props = defineProps({
        employees: {type: Object},
        departments: {type: Object},
    });

    // Form adatai
    const form = useForm({
        name: '', email: '', phone: '', department_id: '',
    });

    const formPage = useForm({});

    const onPageClick = (event) => {
        formPage.get(route('employees.index', {page: event}));
    };

    // Modal ablak megnyitása
    const openModal = (op, name, email, phone, department, employee) => {
        modal.value = true;
        nextTick(() => nameInput.value.focus());
        operation.value = op;
        id.value = employee;
        if(op == 1){
            title.value = 'Create Employee';
        }else{
            title.value = 'Edit Employee';
            form.name = name;
            form.email = email;
            form.phone = phone;
            form.department_id = department;
        }
    };

    const closeModal = () => {
        modal.value = false;
        form.reset();
    };

    const save = () => {
        if( operation.value == 1 ){
            form.post(route('employees.store'), {
                onSuccess: () => { ok('Employee created') }
            });
        }else{
            form.put(route('employees.update', id.value), {
                onSuccess: () => { ok('Employee updated') }
            });
        }
    };

    const ok = (message) => {
        form.reset();
        closeModal();
        Swal.fire({title: message, icon:'success'});
    };

    const deleteEmployee = (id, name) => {
        const alerta = Swal.mixin({
            buttonsStyling: true
        });
        alerta.fire({
            title: 'Are you sure delete ' + name + '?',
            icon: 'question', 
            showCancelButton: true, 
            confirmButtonText: '<i class="fa-solid fa-check"></i> Yes, delete it!',
            cancelButtonText: '<i class="fa-solid fa-ban"></i> Cancel!',
        })
        .then((result) => {
            if(result.isConfirmed){
                form.delete(route('employees.destroy', id), {
                    onSuccess: () => { ok('Employee deleted') }
                });
            }
        });
    };
</script>

<template>
    
    <Head title="Employees" />

    <AuthenticatedLayout>

        <!-- HEADER -->
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Employees</h2>
        </template>

        <!-- CONTENT -->
        <template #main>
            <div class="py-12">

                <div class="bg-white grid v-screen place-items-center">
                    <div class="mt-3 mb-3 flex">

                        <!-- "Új elem" gomb modal megjelenítésével -->
                        <PrimaryButton @click="openModal(1)">
                            <i class="fa-solid fa-plus-circle"></i> Add New
                        </PrimaryButton>

                        <!-- "Új elem" gomb "Create" oldalra irányítással -->
                        <!--<Link :href="route('employees.create')"
                            :class="'px-4 py-2 bg-gray-800 text-white border rounded-md font-semibold text-xs'">
                            <i class="fa-solid fa-plus-circle"></i> Add New
                        </Link>-->

                    </div>
                </div>

                <!-- TÁBLÁZAT -->
                <div class="bg-white grid v-screen place-items-center overflow-x-auto">
                    <table class="table-auto border border-gray-400">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="px-2 py-2">#</th>
                                <th class="px-2 py-2">NAME</th>
                                <th class="px-2 py-2">EMAIL</th>
                                <th class="px-2 py-2">PHONE</th>
                                <th class="px-2 py-2">DEPARTMENT</th>
                                <th class="px-2 py-2"></th>
                                <th class="px-2 py-2"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="emp, i in employees.data" :key="emp.id">
                                <td class="border border-gray-400 px-2 py-2">{{ (i+1) }}</td>
                                <td class="border border-gray-400 px-2 py-2">{{ emp.name }}</td>
                                <td class="border border-gray-400 px-2 py-2">{{ emp.email }}</td>
                                <td class="border border-gray-400 px-2 py-2">{{ emp.phone }}</td>
                                <td class="border border-gray-400 px-2 py-2">{{ emp.department }}</td>

                                <td class="border border-gray-400 px-2 py-2">

                                    <!-- "Szerkesztés" gomb modal megjelenítéséval -->
                                    <WarningButton @click="openModal(2, emp.name, emp.email, emp.phone, emp.department_id, emp.id)">
                                        <i class="fa-solid fa-edit"></i>
                                    </WarningButton>

                                    <!-- "Szerkesztés" gomb "Edit" oldalra irányítással -->
                                    <!--<Link :href="route('employees.edit', emp.id)"
                                        :class="'px-4 py-2 bg-yellow-400 text-white border rounded-md font-semibold text-xs'">
                                        <i class="fa-solid fa-edit"></i>
                                    </Link>-->

                                </td>

                                <td class="border border-gray-400 px-2 py-2">

                                    <!-- "Törlés" gomb -->
                                    <DangerButton @click="$event => deleteEmployee(emp.id, emp.name)">
                                        <i class="fa-solid fa-trash"></i>
                                    </DangerButton>

                                </td>
                                
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- PAGINATION -->
                <div class="bg-white grid v-screen place-items-center">
                    <VueTailwindPagination 
                        :current="employees.currentPage" 
                        :total="employees.total"
                        :per-page="employees.perPage" 
                        @page-changed="$event => onPageClick($event)"
                    ></VueTailwindPagination>
                </div>
            </div>
            <Modal :show="modal" @close="closeModal">
                <h2 class="p-3 text-lg font.medium text-hray-900">{{ title }}</h2>

                <div class="p-3">
                    <InputLabel for="name" value="Name:"></InputLabel>
                    <TextInput 
                        id="name" 
                        ref="nameInput" 
                        v-model="form.name" 
                        type="text" 
                        class="mt-1 block w-3/4" 
                        placeholder="Name"
                    ></TextInput>
                    <InputError :message="form.errors.name" class="mt-2"></InputError>
                </div>

                <div class="p-3">
                    <InputLabel for="email" value="Email:"></InputLabel>
                    <TextInput 
                        id="email" 
                        v-model="form.email" 
                        type="text" 
                        class="mt-1 block w-3/4" 
                        placeholder="Email"
                    ></TextInput>
                    <InputError :message="form.errors.email" class="mt-2"></InputError>
                </div>

                <div class="p-3">
                    <InputLabel for="phone" value="Phone:"></InputLabel>
                    <TextInput 
                        id="phone" 
                        v-model="form.phone" 
                        type="text" 
                        class="mt-1 block w-3/4" 
                        placeholder="Phone"
                    ></TextInput>
                    <InputError :message="form.errors.phone" class="mt-2"></InputError>
                </div>

                <div class="p-3">
                    <InputLabel for="department_id" value="Department:"></InputLabel>
                    <SelectInput 
                        :options="departments" 
                        id="department_id" 
                        v-model="form.department_id" 
                        type="text" 
                        class="mt-1 block w-3/4" 
                    ></SelectInput>
                    <InputError :message="form.errors.department_id" class="mt-2"></InputError>
                </div>

            </Modal>
        </template>
        
    </AuthenticatedLayout>
</template>