import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, Link, router, usePage } from "@inertiajs/react";
import React, { useState } from "react";
import FilePreview from "@/Components/FilePreview";

const Create = () => {
    const { errors } = usePage().props
    const [file, setFile] = useState(null);
    const [values, setValues] = useState({
        name: "",
        email: "",
        logo: null,
        website: "",
      })

      const handleFileChange = (selectedFile) => {
        setFile(selectedFile); // Receive file from child component
        setValues((prevValues) => ({ ...prevValues, logo: selectedFile}));
        // console.log(file);
      };

    function handleChange(e) {
        const key = e.target.id;
        const value = e.target.value
        setValues(values => ({
            ...values,
            [key]: value,
        }))
      }

    // Handle form submission
    const handleSubmit = (e) => {
        e.preventDefault();
        
        // return console.log(values);
        router.post('/company', values)
    };

    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800">
                    Company
                </h2>
            }
        >
            <Head title="Company - Create" />

            <div className="py-12">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div className="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <div className="p-6">
                            <div className="flex justify-between items-center mb-4">
                                <h1 className="text-xl font-bold mb-4">Create Company</h1>
                                <Link
                                    href={route('company.index')}
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
                                            <input type="text" id="name" className="rounded w-full" placeholder="Enter name" defaultValue={values.name} onKeyUp={handleChange} />
                                            {errors.name && <div className="text-red-500">{errors.name}</div>}
                                        </div>
                                        <div className="mb-4">
                                            <label htmlFor="email">Email <span className="text-red-500">*</span></label>
                                            <br />
                                            <input type="email" id="email" className="rounded w-full" placeholder="Enter email" defaultValue={values.email} onKeyUp={handleChange} />
                                            {errors.email && <div className="text-red-500">{errors.email}</div>}
                                        </div>
                                        <div className="mb-4">
                                            <label htmlFor="file">Logo <span className="text-red-500">*</span></label>
                                            <br />
                                            {/* <input type="file" className="rounded w-full" placeholder="Enter name" /> */}
                                            <FilePreview setFile={setFile} file={file} onFileChange={handleFileChange} />
                                            {errors.logo && <div className="text-red-500">{errors.logo}</div>}
                                        </div>
                                        <div className="mb-4">
                                            <label htmlFor="website">Website</label>
                                            <br />
                                            <input type="text" id="website" className="rounded w-full" placeholder="Enter website" defaultValue={values.website} onKeyUp={handleChange} />
                                            {errors.website && <div className="text-red-500">{errors.website}</div>}
                                        </div>

                                        <div className="text-end">
                                            <button type="submit" className="rounded text-white bg-black px-4 py-2">Create</button>
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

export default Create;
