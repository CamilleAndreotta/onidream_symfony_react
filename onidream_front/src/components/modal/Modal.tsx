interface ModalProps {
    isOpen: boolean;
    onClose: () => void;
    children: React.ReactNode
}

const Modal: React.FC<ModalProps> = ({
    isOpen,
    onClose,
    children
}) => {

    if(!isOpen) return null;

    return (
        <div className="fixed inset-0 bg-cyan-950 flex items-center justify-center z-50">
            <div className="bg-white p-6 max-h-10/12 rounded-lg shadow-lg max-w-2xl w-full relative overflow-y-scroll">
                <div>{children}</div>
                <button
                onClick={onClose}
                className="mt-6 px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600"
                >
                Fermer
                </button>
            </div>
        </div>
    )
}

export default Modal;