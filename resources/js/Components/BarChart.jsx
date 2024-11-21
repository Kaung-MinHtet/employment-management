// src/components/BarChart.js

import React from 'react';
import { Bar } from 'react-chartjs-2';
import { Chart as ChartJS, CategoryScale, LinearScale, BarElement, Title, Tooltip, Legend } from 'chart.js';

// Register required Chart.js components
ChartJS.register(CategoryScale, LinearScale, BarElement, Title, Tooltip, Legend);

const BarChart = ({ companies, employee_per_company }) => {
// console.log(employee_per_company);
  const data = {
    labels: companies,
    datasets: [
      {
        label: 'Employee Count',
        data: employee_per_company,
        backgroundColor: 'rgba(75, 192, 192, 0.2)',
        borderColor: 'rgba(75, 192, 192, 1)',
        borderWidth: 1,
      },
    ],
  };

  const options = {
    responsive: true,
    plugins: {
      legend: {
        position: 'top',
      },
      title: {
        display: true,
        text: 'Employee Data',
      },
    },
  };

  return (
    <div>
      <h2 className='font-bold'>Employee Bar Chart</h2>
      <Bar data={data} options={options} />
    </div>
  );
};

export default BarChart;