import { ButtonProps } from "../@types/Button";

const Button: React.FC<ButtonProps> = ({
  onClick,
  children,
  className,
  type,
  disabled,
}) => {
  return (
    <button
      onClick={onClick}
      className={className}
      type={type}
      disabled={disabled}
    >
      {children}
    </button>
  );
};

export default Button;