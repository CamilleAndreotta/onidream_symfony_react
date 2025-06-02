import { Link } from 'react-router-dom';
import { useAuth } from '../../context/AuthContext';
import { ReactNode } from 'react';
import SideBar from './SideBar';

interface LayoutProps {
    children?: ReactNode;
}


const Layout: React.FC<LayoutProps> = ({ children } = {}) => {
  const { isAuthenticated, logoutUser } = useAuth();

  return (
    <div className="layout flex flex-col min-h-screen">
      { isAuthenticated ? (
        <header style={{ padding: '1rem', background: '#333', color: 'white' }}
        className='col-start-1 col-end-13 h-16'
        >
            
              <nav className='flex flex-row justify-between'>
                <Link className='uppercase font-bold' to="/dashboard" style={{ marginRight: '1rem', color: 'white' }}>Onidream</Link>
                {isAuthenticated ? (
                  <button onClick={logoutUser} style={{ color: 'white' }}>Déconnexion</button>
                ) : (
                  <Link to="/" style={{ color: 'white' }}>Se connecter</Link>
                )}
              </nav>
            
        </header>
        ) : null
      }

      <div className='flex flex-1'>
        <SideBar/>
        <main className=' flex-1 p-4 overflow-auto bg-gray-100'>
          {children}
        </main>
      </div>

      

      <footer className="h-8 bg-amber-200 flex items-center justify-center"
      >
        <p>© 2025 Onidream </p>
      </footer>
    </div>
  );
};

export default Layout;