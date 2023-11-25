import { useContext, useEffect } from "react";
import { Link, useLocation } from "react-router-dom";
import Navigation from "./Navigation";
import { BackdropContext } from "../../contexts/BackdropProvider";

const Header = () => {
    const { dispatch } = useContext(BackdropContext);
    const location = useLocation();

    useEffect(() => {
        dispatch({ type: "CLOSE" });
    }, [location]);

    return (
        <header>
            <div className='relative p-4 flex-between max-width lg:py-6'>
                <h1
                    className='flex font-bold
            rounded-[50%] text-xl'>
                    <Link to='/'>CrossWave</Link>
                </h1>

                <Navigation />
            </div>
        </header>
    );
};

export default Header;
