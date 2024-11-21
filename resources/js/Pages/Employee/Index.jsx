import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, Link, router, usePage } from "@inertiajs/react";
import React, { useEffect, useState } from "react";
import { DataGrid, GridToolbar } from "@mui/x-data-grid";
import { toast } from 'react-toastify';

const Index = ({ toast_message }) => {
    const { employees } = usePage().props;
    const { storage_url } = usePage().props;

    const [showModal, setShowModal] = useState(false); // Modal visibility state
    const [selectedEmployeeId, setSelectedEmployeeId] = useState(null); // Store the ID of the employee to delete

    const columns = [
        { field: "id", headerName: "ID", width: 100 },
        {
            field: "actions",
            headerName: "Actions",
            width: 200,
            sortable: false,
            renderCell: (params) => {
              return (
                <div className="flex justify-center items-center gap-x-4">
                  <button
                    className="bg-orange-500 text-white rounded block px-4"
                    onClick={() => handleEdit(params.row)}
                  >
                    Edit
                  </button>
                  <button
                    onClick={() => handleDelete(params.row.id)}
                    className="bg-red-500 text-white rounded block px-4 py-0"
                  >
                    Delete
                  </button>
                </div>
              );
            }
        },
        { field: "companyName", headerName: "Company", width: 200, renderCell: (params) => {
            return params.row.company.name;
        } },
        { field: "profile_picture", headerName: "Profile Picture", width: 150, renderCell: (params) => {
            if(params.row.profile_picture != null) {
                return (
                    <img src={window.location.origin + "/storage/" + params.value} alt="Profile Picture" className="w-10 h-10 rounded-full" />
                )
            } else {
                return "";
            }
        } },
        { field: "name", headerName: "Name", width: 200 },
        { field: "email", headerName: "Email", width: 250 },
        { field: "phone", headerName: "Phone", width: 200 },
        { field: "created_at", headerName: "Created At", width: 200, renderCell: (params) => {
            let date = new Date(params.row.created_at)
            return (
                <span>{date.toLocaleString()}</span>
            )
        } },
    ];

    const handleEdit = (row) => {
        // console.log('Edit row:', row);
        router.get('/employee/' + row.id + '/edit')
      };
      
    const handleDelete = (id) => {
        // console.log('Delete row with id:', id);
        setSelectedEmployeeId(id); // Set the employee ID for deletion
        setShowModal(true); // Show the confirmation modal
    };

    const confirmDelete = () => {
        // Trigger the delete request to Laravel
        router.delete(route('employee.destroy', selectedEmployeeId), {
            onSuccess: () => {
                // toast.success('Employee deleted successfully'); // Show success toast
                setShowModal(false); // Close the modal
                setSelectedEmployeeId(null); // Clear the selected employee ID
            },
            onError: () => {
                toast.error('Failed to delete employee'); // Show error toast
            }
        });
    };

    const cancelDelete = () => {
        setShowModal(false); // Close the modal without deleting
    };


    useEffect(() => {
        if (toast_message) {
          toast.success(toast_message); // Show the toast with the message from Laravel
        }
        // console.log(employees);
      }, [toast_message]);

    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800">
                    Employee
                </h2>
            }
        >
            <Head title="Employee" />

            <div className="py-12">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div className="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <div className="p-6">
                            <div className="flex justify-between items-center mb-4">
                                <h1 className="text-xl font-bold mb-4">Employees</h1>
                                <Link
                                    href={route('employee.create')}
                                    className="px-3 py-2 rounded-md border border-black text-black hover:bg-black hover:text-white"
                                >
                                    Create
                                </Link>
                            </div>
                            <DataGrid
                                rows={employees.data}
                                columns={columns}
                                slots={{ toolbar: GridToolbar }}
                                    slotProps={{
                                        toolbar: {
                                            showQuickFilter: true,
                                        },
                                }}
                                pageSize={5}
                                rowsPerPageOptions={[5]}
                                pagination
                                getRowId={(row) => row.id}
                            />
                        </div>
                    </div>
                </div>
            </div>

            {/* Modal for Delete Confirmation */}
            {showModal && (
                <div className="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center">
                    <div className="bg-white p-6 rounded-md">
                        <h3 className="text-lg font-semibold mb-4">Are you sure you want to delete this employee?</h3>
                        <div className="flex justify-end gap-4">
                            <button
                                className="px-4 py-2 bg-red-500 text-white rounded"
                                onClick={confirmDelete}
                            >
                                Yes, Delete
                            </button>
                            <button
                                className="px-4 py-2 bg-gray-300 text-black rounded"
                                onClick={cancelDelete}
                            >
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>
            )}
        </AuthenticatedLayout>
    );
};

export default Index;
