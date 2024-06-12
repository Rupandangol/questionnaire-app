import React from 'react';

const QuestionnaireList = ({ allQuestionnaire, currentPage, totalPages, goToPage }) => {
    return (
        <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg p-10">
            <h1 className="text-2xl font-bold mb-4">Active Questionnaire List:</h1>
            <ul className="divide-y divide-gray-200">
                {allQuestionnaire.map(questionnaire => (
                    <li key={questionnaire?.id} className="py-4">
                        <div className="flex items-center justify-between">
                            <div>
                                <h2 className="text-lg font-bold">{questionnaire?.title}</h2>
                                <p className="text-sm text-gray-500">Expiry Date: {questionnaire?.expiry_date}</p>
                                <p className="text-sm text-gray-500">Questionnaire Id: {questionnaire?.id}</p>
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
    );
};

export default QuestionnaireList;
