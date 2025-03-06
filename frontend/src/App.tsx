import React from 'react';
import { BrowserRouter as Router, Routes, Route } from 'react-router-dom';
import { ToastContainer } from 'react-toastify';
import PropertyForm from './components/PropertyForm';
import PropertyList from './pages/PropertyList';

const App: React.FC = () => {
  return (
    <Router>
      <div className="min-h-screen w-screen flex items-center justify-center bg-gray-100">
        <Routes>
          <Route path="/" element={<PropertyList />} />
          <Route path="/property-form" element={<PropertyForm />} />
        </Routes>
        
        <ToastContainer /> 
      </div>
    </Router>
  );
};

export default App;
