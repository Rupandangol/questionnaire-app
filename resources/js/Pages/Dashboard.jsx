import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';
import axios from 'axios';
import { useEffect, useState } from 'react';
import Swal from 'sweetalert2';

export default function Dashboard({ auth }) {
    const [allQuestionnaire, setAllQuestionnaire]=useState([]);
    const [currentPage, setCurrentPage] = useState(1);
    const [totalPages, setTotalPages] = useState(1);

    const [formData, setFormData] = useState({
        title: '',
        expiry_date: ''
      });

      const handleChange = (e) => {
        const { name, value } = e.target;
        setFormData(prevState => ({
          ...prevState,
          [name]: value
        }));
      };
    
      const handleSubmit = async(e) => {
        e.preventDefault();
            try {
                const response = await axios.post('/api/generate-questionnaire', formData);
                if(response.data.status==='success'){
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Questionnaire generated successfully!',
                      });
                    console.log(response.data);
                    setFormData({
                        title:'',
                        expiry_date:''
                    });
                    fetchAllQuestionnaire(1);
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Failed',
                    text: error,
                  });
                console.error('Error:', error);
            }
      };

      const fetchAllQuestionnaire= async(page)=>{
        try{    
            const response= await axios.get(`/api/all-questionnaire?page=${page}`);
            if(response.data.status=='success'){
                setAllQuestionnaire(response.data.data.data);
                setCurrentPage(response.data.data.current_page);
                setTotalPages(response.data.data.last_page);
            }
            console.log(response.data);

        }catch(error){
            console.log(error)
        }
      }

      useEffect(()=>{
        fetchAllQuestionnaire(currentPage);
      },[currentPage]);

      const goToPage = (page) => {
        setCurrentPage(page);
      };
    return (
        <AuthenticatedLayout
            user={auth.user}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>}
        >
            <Head title="Dashboard" />

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg p-10">
                        <form onSubmit={handleSubmit} className="space-y-4">
                            <div>
                            <label className="block mb-1">Title:</label>
                            <input 
                                type="text" 
                                name="title" 
                                value={formData.title} 
                                onChange={handleChange} 
                                required 
                                className="w-full border border-gray-300 rounded-md px-3 py-2"
                            />
                            </div>
                            <div>
                            <label className="block mb-1">Expiry Date:</label>
                            <input 
                                type="date" 
                                name="expiry_date" 
                                value={formData.expiry_date} 
                                onChange={handleChange} 
                                required 
                                className="w-full border border-gray-300 rounded-md px-3 py-2"
                            />
                            </div>
                            <button type="submit" className="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600">Generate Questionnaire</button>
                        </form>
                    </div>
                </div>
            </div>
            <div className="py-2">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg p-10">
                            <h1 className="text-2xl font-bold mb-4">Active Questionnaire List: </h1>
                            <ul className="divide-y divide-gray-200">
                                {allQuestionnaire.map(questionnaire => (
                                <li key={questionnaire?.id} className="py-4">
                                    <div className="flex items-center justify-between">
                                    <div>
                                        <h2 className="text-lg font-bold">{questionnaire?.title}</h2>
                                        <p className="text-sm text-gray-500">Expiry Date: {questionnaire?.expiry_date} </p>
                                        <p className="text-sm text-gray-500">Questionnaire Id: {questionnaire?.id} </p>
                                    </div>
                                    </div>
                                </li>
                                ))}
                            </ul>
                            <div className="flex justify-between mt-4">
                                <button
                                    className="px-4 py-2 bg-gray-200 text-gray-600 rounded hover:bg-gray-300"
                                    disabled={currentPage === 1}
                                    onClick={() => goToPage(currentPage - 1)}
                                    >
                                    Previous
                                </button>
                                <button
                                    className="px-4 py-2 bg-gray-200 text-gray-600 rounded hover:bg-gray-300"
                                    disabled={currentPage === totalPages}
                                    onClick={() => goToPage(currentPage + 1)}
                                    >
                                    Next
                                </button>
                            </div>
                    </div>
                </div>
            </div>
            
        </AuthenticatedLayout>
    );
}
