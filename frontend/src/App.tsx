import React from 'react';
import { ToastContainer } from 'react-toastify';
import OwnerForm from './components/OwnerForm';
import PropertyForm from './components/PropertyForm';
import PropertyList from './pages/PropertyList';

const App: React.FC = () => {
  return (
    <div className="min-h-screen w-screen flex items-center justify-center bg-gray-100">
      {/* <OwnerForm /> */}
      {/* <PropertyForm /> */}
      <PropertyList />
      <ToastContainer /> 
    </div>
  );
};

export default App;
