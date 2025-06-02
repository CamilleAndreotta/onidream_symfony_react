import EditorLine from "../../components/lines/EditorLine";
import PageLoading from "../../components/spinner/PageLoading";
import { useEditors } from "../../context/EditorContext";

const ListEditorPage = () => {
    const {editors, loading} = useEditors();

    if(loading) {
        return <PageLoading />
    }

    return (
        <div className="w-full h-full bg-amber-50 p-4" >
            <h1 className="flex w-full bg-amber-200 h-20 justify-center items-center rounded-md mb-4 uppercase font-bold">Liste des éditeurs </h1>

            <table className="table-auto border border-gray-400 w-full bg-white">
                <thead>
                    <tr className="bg-gray-200">
                        <th className="border border-gray-300 px-4 py-2 w-2/3" scope="col">Nom de l'éditeur et collection</th>
                        <th className="border border-gray-300 px-4 py-2" scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    {editors && editors.map((editor) => (
                        <EditorLine
                            key={editor.id}
                            editor={editor}
                        />
                    ))}
                </tbody>

            </table>
        </div>
    )
}

export default ListEditorPage;