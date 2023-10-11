<script setup>
    import { Head, Link, useForm } from '@inertiajs/vue3';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import DangerButton from '../../Components/DangerButton.vue';
    import Swal from 'sweetalert2';

    const props = defineProps({
        employees: {
            type: Object
        },
    });

    const form = useForm({
        id: null,
    });

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
                form.delete(route('employees.destroy', id));
            }
        });
    };
</script>

<template>
    <Head title="Employees" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Employees</h2>
        </template>

        <div class="py-12">

            <div class="bg-white grid v-screen place-items-center">
                <div class="mt-3 mb-3 flex">
                    <Link :href="route('employees.create')"
                          :class="'px-4 py-2 bg-gray-800 text-white border rounded-md font-semibold text-xs'">
                        <i class="fa-solid fa-plus-circle"></i> Add New
                    </Link>
                </div>
            </div>

            <div class="bg-white grid v-screen place-items-center">
                <table class="table-auto border border-gray-400">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-4 py-4">#</th>
                            <th class="px-4 py-4">NAME</th>
                            <th class="px-4 py-4">EMAIL</th>
                            <th class="px-4 py-4"></th>
                            <th class="px-4 py-4"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="emp, i in employees" :key="emp.id">
                            <td class="border border-gray-400 px-4 py-4">{{ (i+1) }}</td>
                            <td class="border border-gray-400 px-4 py-4">{{ emp.name }}</td>
                            <td class="border border-gray-400 px-4 py-4">{{ emp.email }}</td>
                            <td class="border border-gray-400 px-4 py-4">
                                <Link :href="route('employees.edit', emp.id)"
                                      :class="'px-4 py-2 bg-yellow-400 text-white border rounded-md font-semibold text-xs'">
                                    <i class="fa-solid fa-edit"></i>
                                </Link>
                            </td>
                            <td class="border border-gray-400 px-4 py-4">
                                <DangerButton @click="$event => deleteEmployee(emp.id, emp.name)">
                                    <i class="fa-solid fa-trash"></i>
                                </DangerButton>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AuthenticatedLayout>
</template>