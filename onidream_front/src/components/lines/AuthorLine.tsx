import { Link } from "react-router-dom";
import { AuthorType } from "../../@types/Author";
import { deleteAuthor } from "../../services/authorService";
import DOMPurify from "dompurify";
import { useAuthors } from "../../context/AuthorContext";
import { useState } from "react";


interface AuthorLineProps {
    author: AuthorType;
}

const AuthorLine = ({author}: AuthorLineProps) => {
    const [isModalOpen, setIsModalOpen] = useState(false);

    const {fetchAuthors} = useAuthors();

    const authorBirthDate = new Date(author.birthDate);
    const authorBirthdateFormatted = authorBirthDate.toLocaleDateString("fr-FR");

    let authorDeathdateFormatted = null;
    if(author.deathDate !== null) {
        const authorDeathDate = new Date(author.deathDate);
        authorDeathdateFormatted = authorDeathDate.toLocaleDateString("fr-FR");
    }

    function truncateHtmlText(html: string, maxLength: number): string {
        const sanitized = DOMPurify.sanitize(html);
        const tempDiv = document.createElement("div");
        tempDiv.innerHTML = sanitized;
      
        const plainText = tempDiv.textContent || tempDiv.innerText || "";
      
        if (plainText.length > maxLength) {
          return plainText.slice(0, maxLength) + "...";
        }
      
        return plainText;
    }

    const text = truncateHtmlText(author.biography,50);

    const handleDelete = async (id: string) => {
        try {
            
            const result = await deleteAuthor(id);
            
            if (result.status === 200){
                fetchAuthors();
            }
            
        } catch (error: any) {
            console.log(error);
        }
    };
 
    return (
        <tr>
            <td className="border border-gray-300 px-4 py-2" scope="row">{author.lastname}</td>
            <td className="border border-gray-300 px-4 py-2" scope="row">{author.firstname}</td>
            <td className="border border-gray-300 px-4 py-2" scope="row">{authorBirthdateFormatted}</td>
            <td className="border border-gray-300 px-4 py-2" scope="row">{authorDeathdateFormatted}</td>
            <td className="border border-gray-300 px-4 py-2 w-1/3" dangerouslySetInnerHTML={{ __html: text }} />
            <td className="border border-gray-300 px-4 py-2">
                <div className="flex flex-row gap-2">
                    <Link className="block w-40 p-0.75 bg-cyan-950 font-bold text-white rounded-sm text-sm text-center hover:scale-105" to={`/author/${author.id}/show`} state={{author}}>Consulter les passages</Link>
                    <Link className="block w-20 p-0.75 bg-cyan-950 font-bold  text-white rounded-sm text-sm text-center hover:scale-105" to={`/author/${author.id}/update`} state={{author}}>Modifier</Link>
                    <button onClick={() => setIsModalOpen(true)} className="block w-20 p-0.75 bg-red-900 font-bold text-white rounded-sm text-sm text-center hover:scale-105" >Supprimer</button>
                    {isModalOpen && (
                    <tr>
                        <td colSpan={5}>
                            <div className="fixed inset-0 flex items-center justify-center bg-cyan-950 bg-opacity-50 z-50">
                                <div className="bg-white rounded-lg shadow-lg p-6 w-full max-w-sm">
                                    <h2 className="text-lg font-semibold mb-4 text-gray-800">Confirmer la suppression</h2>
                                    <p className="mb-6 text-sm">Êtes-vous sûr de vouloir supprimer l'auteur(e) ?</p>
                                    <div className="flex justify-end space-x-3">
                                        <button onClick={() => setIsModalOpen(false)} className="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded">Annuler</button>
                                        <button onClick={() => handleDelete(author.id)} className="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded">Supprimer</button>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                )}
                </div>
            </td>
        </tr>
    )
}

export default AuthorLine;