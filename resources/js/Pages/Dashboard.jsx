import BarChart from '@/Components/BarChart';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';
import { Chart as ChartJS, CategoryScale, LinearScale, PointElement, LineElement, ArcElement, Tooltip, Legend } from "chart.js";
import { Line } from "react-chartjs-2";

ChartJS.register(ArcElement, CategoryScale, LinearScale, PointElement, LineElement, Tooltip, Legend);

export default function Dashboard({ ttl_companies, ttl_employees, recent_companies, recent_employees, user, companies, employee_per_company }) {
    // console.log(employee_per_company);
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
                        <div className='mt-4 md:mt-4 grid grid-cols-1 md:grid-cols-2 gap-4 mx-4 md:mx-0'>
                            <div className="bg-white p-4 shadow">
                                <BarChart companies={companies} employee_per_company={employee_per_company} />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
