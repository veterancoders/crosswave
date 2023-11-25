import { useContext } from "react";
import { createPortal } from "react-dom";
import { BackdropContext } from "../contexts/BackdropProvider";

const Backdrop = () => {
    const { modals, dispatch } = useContext(BackdropContext);

    return (
        <>
            {/* backdrop */}
            {/* port backdrop before the root element in index.html */}
            {createPortal(
                <div
                    className={`top-0 z-10 h-screen w-full bg-[#2626266c] hover:cursor-pointer ${
                        modals.backdrop ? "fixed" : "hidden"
                    }`}
                    onClick={() => dispatch({ type: "CLOSE" })}></div>,
                document.getElementById("backdrop-root")
            )}
        </>
    );
};

export default Backdrop;
