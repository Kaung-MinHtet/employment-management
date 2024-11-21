import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';

export default function Dashboard({ ttl_companies, ttl_employees, recent_companies, recent_employees, user }) {
    console.log(user);
    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800">
                    Dashboard
                </h2>
            }
        >
            <Head title="Dashboard" />

            <div className="py-12">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div className="overflow-hidden sm:rounded-lg">
                        {/* <div className="p-6 text-gray-900">
                            You're logged in!
                        </div> */}
                        <div className="grid grid-cols-1 md:grid-cols-2 gap-4 mx-4 md:mx-0">
                            {
                                user.role == "admin" && <div className="p-6 bg-white shadow text-black flex justify-between items-center">
                                    <h4 className='text-lg font-bold'>Total Companies</h4>
                                    <p>{ttl_companies}</p>
                                </div>
                            }
                            <div className="p-6 bg-white shadow text-black flex justify-between items-center">
                                <h4 className='text-lg font-bold'>Total Employees</h4>
                                <p>{ttl_employees}</p>
                            </div>
                            {
                                user.role == "admin" && <div className="p-6 bg-white shadow text-black flex justify-between items-center">
                                <h4 className='text-lg font-bold'>Recent Companies</h4>
                                    <p>{recent_companies}</p>
                                </div>
                            }
                            <div className="p-6 bg-white shadow text-black flex justify-between items-center">
                                <h4 className='text-lg font-bold'>Recent Employees</h4>
                                <p>{recent_employees}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
