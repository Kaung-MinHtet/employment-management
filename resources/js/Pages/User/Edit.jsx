import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, Link, router, useForm, usePage } from "@inertiajs/react";
import React, { useState } from "react";
import FilePreview from "@/Components/FilePreview";

const Edit = ({ user, companies, storage_url }) => {
    // Use Inertia's useForm hook to handle form submission
    const { data, setData, put, processing, errors } = useForm({
        name: user.name || '',
        email: user.email || '',
        role: user.role || '',
    });

    // Handle the input changes and update the form data
    const handleChange = (e) => {
        setData(e.target.id, e.target.value);
    };

    // Handle form submission
    // const handleSubmit = (e) => {
    //     e.preventDefault();
    //     // console.log(data);
    //     // router.put('/company/' + company.id, data);
    //     put(route('company.update', company.id)); // Trigger PUT request to update company
    // };

    const handleSubmit = (e) => {
        e.preventDefault();
    
        const formData = new FormData();
        Object.keys(data).forEach((key) => {
            formData.append(key, data[key]);
        });
    
        // Add `_method` for Laravel's form method spoofing
        formData.append('_method', 'PUT');
    
        // Use `router.post` for submitting the data
        router.post(route('user.update', user.id), formData, {
            preserveScroll: true, // Optional: Keeps the scroll position
        });
    };
    
    const [values, setValues] = useState({
        name: "",
        email: "",
        role: "",
      })


    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800">
                    User
                </h2>
            }
        >
            <Head title="User - Edit" />

            <div className="py-12">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div className="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <div className="p-6">
                            <div className="flex justify-between items-center mb-4">
                                <h1 className="text-xl font-bold mb-4">Edit User</h1>
                                <Link
                                    href={route('user.index')}
                                    className="px-3 py-2 rounded-md border border-black text-black hover:bg-black hover:text-white"
                                >
                                    List
                                </Link>
                            </div>
                            <div className="grid place-items-center">
                                <div className="w-full max-w-[600px]">
                                    <form onSubmit={handleSubmit}>
                                        <div className="mb-4">
                                            <label htmlFor="name">Name <span className="text-red-500">*</span></label>
                                            <br />
                                            <input type="text" id="name" className="rounded w-full" placeholder="Enter name" defaultValue={data.name} onKeyUp={handleChange} />
                                            {errors.name && <div className="text-red-500">{errors.name}</div>}
                                        </div>
                                        <div className="mb-4">
                                            <label htmlFor="email">Email <span className="text-red-500">*</span></label>
                                            <br />
                                            <input type="email" id="email" className="rounded w-full" placeholder="Enter email" defaultValue={data.email} onKeyUp={handleChange} />
                                            {errors.email && <div className="text-red-500">{errors.email}</div>}
                                        </div>
                                        <div className="mb-4">
                                            <label htmlFor="role">Role</label>
                                            <br />
                                            <select name="role" id="role" htmlFor="role" className="rounded w-full" onChange={handleChange}>
                                                <option selected disabled>Select role</option>
                                                <option value="admin" selected={data.role == "admin" ? true : false}>Admin</option>
                                                <option value="manager" selected={data.role == "manager" ? true : false}>Manager</option>
                                            </select>
                                            {errors.role && <div className="text-red-500">{errors.role}</div>}
                                        </div>
                                        <div className="mb-4">
                                            <label htmlFor="company_id">Company</label>
                                            <br />
                                            <select name="company_id" id="company_id" htmlFor="company_id" className="rounded w-full" onChange={handleChange}>
                                                <option selected disabled>Select Company</option>
                                                {companies.map(company => (
                                                    <option key={company.id} value={company.id} >{company.name}</option>
                                                ))}
                                            </select>
                                            {errors.role && <div className="text-red-500">{errors.role}</div>}
                                        </div>

                                        <div className="text-end">
                                            <button type="submit" className="rounded text-white bg-black px-4 py-2">Update</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
};

export default Edit;
