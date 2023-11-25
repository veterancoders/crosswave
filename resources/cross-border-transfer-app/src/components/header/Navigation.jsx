import { Fragment, useContext } from "react";
import { BackdropContext } from "../../contexts/BackdropProvider";
import NavItem from "./NavItem";

const Pages = ["/home", "/dashboard", "/support", "/about", "/blog"];

const Navigation = () => {
    const { modals, dispatch } = useContext(BackdropContext);

    return (
        <nav className='lg:basis-2/5'>
            <div>
                {/* desktop menu */}
                <ul className='hidden md:flex items-center md:justify-end font-semibold opacity-80'>
                    {Pages.map((page, idx) => (
                        <Fragment key={idx}>
                            <NavItem
                                style={"mr-8"}
                                link={page}
                                pageTitle={page.slice(1)}
                            />
                        </Fragment>
                    ))}
                </ul>

                {/* mobile nav icon */}
                <button
                    className='md:hidden'
                    onClick={() => dispatch({ type: "MOBILEMENU" })}>
                    <svg
                        width='32'
                        height='32'
                        viewBox='0 0 48 48'
                        fill='none'
                        xmlns='http://www.w3.org/2000/svg'>
                        <rect
                            x='8'
                            y='8'
                            width='32'
                            height='32'
                            stroke='black'
                            strokeWidth='16'
                        />
                        <path d='M0 0H48V48H0V0Z' fill='#ededed' />
                        <path d='M7 10H41' stroke='black' strokeWidth='2' />
                        <path d='M7 24H41' stroke='black' strokeWidth='2' />
                        <path d='M7 38H41' stroke='black' strokeWidth='2' />
                    </svg>
                </button>

                {/* mobile menu */}
                <div
                    className={`fixed lg:hidden top-0 right-0 z-20 bg-white h-screen w-[75%]
                   rounded-tl-lg rounded-bl-lg duration-200 ease-out ${
                       modals.mobileMenu ? "translate-x-0" : "translate-x-full"
                   }`}>
                    {/* <button className='absolute top-[5%] right-[10%] font-bold'>
                      X
                  </button> */}

                    <div className='px-8 py-16'>
                        <ul className='flex-center flex-col font-semibold'>
                            {Pages.map((page, idx) => (
                                <Fragment key={idx}>
                                    <NavItem
                                        style={"my-3"}
                                        link={page}
                                        pageTitle={page.slice(1)}
                                    />
                                </Fragment>
                            ))}
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
    );
};

export default Navigation;
