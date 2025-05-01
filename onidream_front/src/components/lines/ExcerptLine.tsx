import { Link } from "react-router-dom";
import { ExcerptType } from "../../@types/Excerpt";
import DOMPurify from "dompurify";
import { deleteExcerpt } from "../../services/excerptService";
import { AuthorType } from "../../@types/Author";
import { useState } from "react";


interface ExcerptLineProps {
    excerpt: ExcerptType
    onDelete: (id: string) => void;
}

const ExcerptLine = ({excerpt, onDelete}: ExcerptLineProps) => {
    const [isModalOpen, setIsModalOpen] = useState(false);

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

    const text = truncateHtmlText(excerpt.text,50);

    const handleDelete = async (id: string) => {
        try {
            
            const result = await deleteExcerpt(id);
            
            if (result.status === 200){
                onDelete(id);
            }
            
        } catch (error: any) {
            console.log(error);
        }
    };
 
    return (
        <>
            <tr>
                <th className="border border-gray-300 px-4 py-2" scope="row">{excerpt.books.name}</th>
                <td className="border border-gray-300 px-4 py-2">{excerpt.books.authors.map((author: AuthorType, index: number) => <div key={index}> {author.firstname} {author.lastname}</div> )}</td>
                <td className="border border-gray-300 px-4 py-2 w-1/3" dangerouslySetInnerHTML={{ __html: text }} />
                <td className="border border-gray-300 px-4 py-2">
                    <div className="flex flex-row gap-2 ">
                        {excerpt.categories.map((category, index)=> (
                            <span key={index} className="block min-w-20 p-0.75 bg-amber-400 font-bold rounded-sm text-sm text-center">
                                {category.name}
                            </span>
                        ))}
                    </div> 
                </td>
                <td className="border border-gray-300 px-4 py-2">
                    <div className="w-full flex flex-row gap-2">
                        <Link className="block w-40 p-0.75 bg-cyan-950 font-bold text-white rounded-sm text-sm text-center hover:scale-105" to={`/excerpt/${excerpt.id}/show`} state={{excerpt}}>Consulter l'extrait</Link>
                        <Link className="block w-20 p-0.75 bg-cyan-950 font-bold  text-white rounded-sm text-sm text-center hover:scale-105" to={`/excerpt/${excerpt.id}/update`} state={{excerpt}}>Modifier</Link>
                        <button onClick={() => setIsModalOpen(true)} className="block w-20 p-0.75 bg-red-900 font-bold text-white rounded-sm text-sm text-center hover:scale-105" >Supprimer</button>
                    </div>
                </td>
            </tr>
            {isModalOpen && (
                <tr>
                    <td colSpan={5}>
                        <div className="fixed inset-0 flex items-center justify-center bg-cyan-950 bg-opacity-50 z-50">
                            <div className="bg-white rounded-lg shadow-lg p-6 w-full max-w-sm">
                                <h2 className="text-lg font-semibold mb-4 text-gray-800">Confirmer la suppression</h2>
                                <p className="mb-6 text-sm">Êtes-vous sûr de vouloir supprimer cet extrait ?</p>
                                <div className="flex justify-end space-x-3">
                                    <button onClick={() => setIsModalOpen(false)} className="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded">Annuler</button>
                                    <button onClick={() => handleDelete(excerpt.id)} className="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded">Supprimer</button>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            )}
        </>
    )
}

export default ExcerptLine;