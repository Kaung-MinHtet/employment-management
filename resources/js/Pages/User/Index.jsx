import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, Link, router, usePage } from "@inertiajs/react";
import React, { useEffect, useState } from "react";
import { DataGrid, GridToolbar } from "@mui/x-data-grid";
import { toast } from 'react-toastify';

const Index = ({ toast_message }) => {
    const { users } = usePage().props;
    const { storage_url } = usePage().props;

    const [showModal, setShowModal] = useState(false); // Modal visibility state
    const [selectedUserId, setSelectedUserId] = useState(null); // Store the ID of the user to delete

    const columns = [
        { field: "id", headerName: "ID", width: 100 },
        {
            field: "actions",
            headerName: "Actions",
            width: 200,
            sortable: false,
            renderCell: (params) => {
              return params.row.email != "admin@admin.com" && (
                <div className="flex justify-center items-center gap-x-4">
                  <button
                    className="bg-orange-500 text-white rounded block px-4"
                    onClick={() => handleEdit(params.row)}
                  >
                    Edit
                  </button>
                </div>
              );
            }
        },
        { field: "name", headerName: "Name", width: 200 },
        { field: "email", headerName: "Email", width: 250 },
        { field: "role", headerName: "Role", width: 250 },
        { field: "companyName", headerName: "Company", width: 200, renderCell: (params) => {
            return params.row.company ? params.row.company.name : "";
        } },
        { field: "created_at", headerName: "Created At", width: 200, renderCell: (params) => {
            let date = new Date(params.row.created_at)
            return (
                <span>{date.toLocaleString()}</span>
            )
        } },
    ];

    const handleEdit = (row) => {
        // console.log('Edit row:', row);
        router.get('/user/' + row.id + '/edit')
      };



    useEffect(() => {
        if (toast_message) {
          toast.success(toast_message); // Show the toast with the message from Laravel
        }
        
      }, [toast_message]);

    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800">
                    User
                </h2>
            }
        >
            <Head title="User" />

            <div className="py-12">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div className="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <div className="p-6">
                            <div className="flex justify-between items-center mb-4">
                                <h1 className="text-xl font-bold mb-4">Users</h1>
                                
                            </div>
                            <DataGrid
                                rows={users.data}
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

        </AuthenticatedLayout>
    );
};

export default Index;
