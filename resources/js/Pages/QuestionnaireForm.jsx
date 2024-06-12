import React, { useState, useEffect } from 'react';
import axios from 'axios';
import { usePage } from '@inertiajs/react';
import Swal from 'sweetalert2';

const QuestionnaireForm = () => {
  const [questionnaire,setQuestionnaire]=useState([]);
  const [questions, setQuestions] = useState([]);
  const [answers, setAnswers] = useState({});
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);

  const { id, student_id } = usePage().props;

  useEffect(() => {
    // Fetch the questionnaire data from the API
    const fetchQuestionnaireData = async () => {
      try {
        const response = await axios.get(`/api/questionnaire/${id}`);
        if(response.data.status=='success'){
          setQuestionnaire(response.data.data);
          setQuestions(response.data.data.questionnaire_details);
        }
        else{
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Something went wrong',
          });
        }

      } catch (error) {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'Failed to fetch questionnaire data',
        });
        setError('This questionnaire is already expired');
      }
    };

    fetchQuestionnaireData();
  }, []);

  const handleAnswerChange = (questionId, answerId) => {
    console.log('question id ===?>',questionId);
    setAnswers({ ...answers, [questionId]: answerId });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);
    setError(null);

    try {
      // Prepare the payload from selected answers
      const payload ={
        response: Object.entries(answers).map(([questionId, answerId]) => ({
          question_id: parseInt(questionId), // questionId is parsed as an integer
          answer_id: parseInt(answerId), // answerId is parsed as an integer
        }))
      }

      // Submit the payload to the API endpoint
      const response = await axios.post(`/api/questionnaire/${id}/student/${student_id}`, payload);

      if(response.data.status=='success'){
        Swal.fire({
          icon: 'success',
          title: 'Submitted',
          text: 'Questionnaire submitted successfully!',
        });
        console.log(response.data); // Handle success response
      }
      // Clear selected answers
      setAnswers({});
    } catch (error) {
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'You have already submitted !!',
      });
      setError('You have already submitted !!');
    }
 
    setLoading(false);
  };

  if (loading) return <div className='h-screen flex items-center justify-center'>Loading...</div>;
  if (error) return <div className='h-screen flex items-center justify-center'>Error: {error}</div> ;

  return (
    <div className="container mx-auto p-5">
    <div className="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
      <div className="flex justify-between">
        <h1 className="text-2xl font-bold mb-4">Questionnaire === {questionnaire?.title} </h1>
        <p> Expiry date: {questionnaire?.expiry_date}</p>
      </div>
      <form onSubmit={handleSubmit}>
        {questions.map((question) => (
          <div key={question.question_id} className="mb-4">
            <p className="font-semibold">{question.questions.question}</p>
            <div className="ml-4">
              {question.questions.answers.map((answer) => (
                <label key={answer.id} className="block mb-2">
                  <input
                    type="radio"
                    required
                    name={`answer_${question.question_id}`}
                    value={answer.id}
                    checked={answers[question.question_id] === answer.id}
                    onChange={() => handleAnswerChange(question.question_id, answer.id)}
                    className="mr-2"
                  />
                  {answer.answer}
                </label>
              ))}
            </div>
          </div>
        ))}
        <button
          type="submit"
          className="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
        >
          Submit
        </button>
      </form>
    </div>
  </div>
  );
};

export default QuestionnaireForm;
