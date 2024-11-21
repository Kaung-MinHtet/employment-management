import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, Link, router, useForm, usePage } from "@inertiajs/react";
import React, { useState } from "react";
import FilePreview from "@/Components/FilePreview";

const Edit = ({ employee, companies, storage_url }) => {
    // Use Inertia's useForm hook to handle form submission
    const { data, setData, put, processing, errors } = useForm({
        name: employee.name || '',
        email: employee.email || '',
        phone: employee.phone || '',
        profile_picture: employee.profile_picture || null,
        company_id: employee.company_id || '',
    });

    // Handle the input changes and update the form data
    const handleChange = (e) => {
        setData(e.target.id, e.target.value);
    };

    const handleSubmit = (e) => {
        e.preventDefault();
    
        const formData = new FormData();
        Object.keys(data).forEach((key) => {
            formData.append(key, data[key]);
        });
    
        // Add `_method` for Laravel's form method spoofing
        // formData.append('_method', 'PUT');
    
        // Use `router.post` for submitting the data
        router.post(route('employee.update', employee.id), formData, {
            preserveScroll: true, // Optional: Keeps the scroll position
        });
    };
    
    const [file, setFile] = useState(null);
    const [oldPreview, setOldPreview] = useState(employee.profile_picture);
    const [values, setValues] = useState({
        name: "",
        email: "",
        phone: "",
        profile_picture: null,
        company_id: "",
      })

    const handleFileChange = (selectedFile) => {
    setOldPreview(null);
    setFile(selectedFile); // Receive file from child component
    setValues((prevValues) => ({ ...prevValues, profile_picture: selectedFile}));
    setData("profile_picture", selectedFile);
    // console.log(file);
    };

    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800">
                    Employee
                </h2>
            }
        >
            <Head title="Employee - Edit" />

            <div className="py-12">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div className="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <div className="p-6">
                            <div className="flex justify-between items-center mb-4">
                                <h1 className="text-xl font-bold mb-4">Edit Employee</h1>
                                <Link
                                    href={route('employee.index')}
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
                                            <label htmlFor="phone">Phone <span className="text-red-500">*</span></label>
                                            <br />
                                            <input type="text" id="phone" className="rounded w-full" placeholder="Enter phone" defaultValue={data.phone} onKeyUp={handleChange} />
                                            {errors.phone && <div className="text-red-500">{errors.phone}</div>}
                                        </div>
                                        <div className="mb-4">
                                            <label htmlFor="file">Profile Picture <span className="text-red-500">*</span></label>
                                            <br />
                                            {/* <input type="file" className="rounded w-full" placeholder="Enter name" /> */}
                                            <FilePreview setFile={setFile} file={file} onFileChange={handleFileChange} />
                                            {oldPreview && <img src={window.location.origin + "/storage/" + data.profile_picture} id="edit-preview" className="w-[100px] h-auto rounded" />}
                                            {errors.profile_picture && <div className="text-red-500">{errors.profile_picture}</div>}
                                        </div>
                                        
                                        <div className="mb-4">
                                            <label htmlFor="company_id">Company <span className="text-red-500">*</span></label>
                                            <br />
                                            <select id="company_id" className="rounded w-full" onChange={handleChange}>
                                                <option value="" selected disabled>Choose company</option>
                                                {companies.map((company) => (
                                                    <option key={company.id} value={company.id} selected={data.company_id == company.id}>{company.name}</option>
                                                    ))}
                                            </select>
                                            {errors.company_id && <div className="text-red-500">{errors.company_id}</div>}
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
