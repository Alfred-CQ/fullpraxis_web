import { ImgHTMLAttributes } from 'react';

export default function AppLogoIcon(props: ImgHTMLAttributes<HTMLImageElement>) {
    return (
        <img
            src="/fullpraxis_logo.png" // Ruta de la imagen en el directorio public
            alt="Full Praxis Logo"
            {...props} // Aplica cualquier otra propiedad (como clases o estilos)
        />
    );
}
