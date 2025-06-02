import { ClipLoader } from "react-spinners";

const PageLoading = () => {
    return (
        <div className="fixed top-0 left-0 right-0 bottom-0 flex justify-center items-center bg-cyan-950 bg-opacity-50 z-50">
            <ClipLoader color="#3498db" size={50} />
        </div>
    )
}

export default PageLoading;