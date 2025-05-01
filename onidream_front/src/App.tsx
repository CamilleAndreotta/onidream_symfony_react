import { BrowserRouter, Route, Routes } from "react-router-dom";
import './App.css';
import LoginPage from "./pages/LoginPage";
import RegisterPage from "./pages/RegisterPage";
import PrivateRoute from "./components/PrivateRoute";
import Layout from "./components/template/Layout";
import { AppProviders } from "./providers/AppProviders";
import ListExcerptPage from "./pages/Excerpts/ListExcerptPage";
import UpdateExcerptPage from "./pages/Excerpts/UpdateExcerptPage";
import CreateExcerptPage from "./pages/Excerpts/CreateExcerptPage";
import ShowExcerptPage from "./pages/Excerpts/ShowExcerptPage";
import ShowEditorPage from "./pages/Editors/ShowEditorPage";
import ListEditorPage from "./pages/Editors/ListEditorPage";
import UpdateEditorPage from "./pages/Editors/UpdateEditorPage";
import ListCategoryPage from "./pages/Categories/ListCategoryPage";
import ShowCategoryPage from "./pages/Categories/ShowCategoryPage";
import UpdateCategoryPage from "./pages/Categories/UpdateCategoryPage";
import ListBookPage from "./pages/Books/ListBookPage";
import ShowBookPage from "./pages/Books/ShowBookPage";
import UpdateBookPage from "./pages/Books/UpdateBookPage";
import ListAuthorPage from "./pages/Authors/ListAuthorPage";
import ShowAuthorPage from "./pages/Authors/ShowAuthorPage";
import UpdateAuthorPage from "./pages/Authors/UpdateAuthorPage";
import DashBoardPage from "./pages/DashboardPage";
import UpdateUserPage from "./pages/Users/UpdateUserPage";

const App = () => {

  return(
    <BrowserRouter>
      <AppProviders>
        <Layout>
          <Routes>
            <Route path="/" element={<LoginPage />} />
            <Route path="/register" element={<RegisterPage />} />
              <Route element={<PrivateRoute />}>
                <Route path="/dashboard" element={<DashBoardPage />} />
                <Route path="/excerpts" element={<ListExcerptPage />} />
                <Route path="/excerpt" element={<CreateExcerptPage />} />
                <Route path="/excerpt/:id/update" element={<UpdateExcerptPage />} />
                <Route path="/excerpt/:id/show" element={<ShowExcerptPage />} />
                <Route path="/editors" element={<ListEditorPage />}/>
                <Route path="/editor/:id/show" element={<ShowEditorPage/>} />
                <Route path="/editor/:id/update" element={<UpdateEditorPage/>} />
                <Route path="/categories" element={<ListCategoryPage />}/>
                <Route path="/category/:id/show" element={<ShowCategoryPage/>} />
                <Route path="/category/:id/update" element={<UpdateCategoryPage/>} />
                <Route path="/books" element={<ListBookPage />}/>
                <Route path="/book/:id/show" element={<ShowBookPage/>} />
                <Route path="/book/:id/update" element={<UpdateBookPage/>} />
                <Route path="/authors" element={<ListAuthorPage />}/>
                <Route path="/author/:id/show" element={<ShowAuthorPage/>} />
                <Route path="/author/:id/update" element={<UpdateAuthorPage/>} />
                <Route path="/user/:id/update" element={<UpdateUserPage/>} />
              </Route>
          </Routes>
        </Layout>
      </AppProviders>
    </BrowserRouter>
  )
}

export default App
