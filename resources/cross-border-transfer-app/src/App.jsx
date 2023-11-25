import { Routes, Route } from "react-router-dom";

import Layout from "./components/Layout";
import Home from "./routes/Home";
import Dashboard from "./routes/protected/Dashboard";
import BackdropProvider from "./contexts/BackdropProvider";
import Support from "./routes/Support";
import About from "./routes/About";
import Blog from "./routes/Blog";
import TransferModal from "./routes/protected/Transfer";

function App() {
    return (
        <BackdropProvider>
            <Routes>
                <Route element={<Layout />}>
                    <Route path='/' element={<Home />} />
                    <Route path='/dashboard' element={<Dashboard />} />
                    <Route path='/transfer' element={<TransferModal />} />
                    <Route path='/support' element={<Support />} />
                    <Route path='/about' element={<About />} />
                    <Route path='/blog' element={<Blog />} />
                </Route>
            </Routes>
        </BackdropProvider>
    );
}

export default App;
