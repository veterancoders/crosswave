import { Outlet } from "react-router-dom";
import Footer from "./Footer";
import Header from "./header/Header";
import Backdrop from "./Backdrop";

const Layout = () => {
    return (
        <div className='relative'>
            <Header />
            <Backdrop />

            <main className='min-h-screen max-width p-4'>{<Outlet />}</main>

            <Footer />
        </div>
    );
};

export default Layout;
