import React, { useState } from 'react';
import 'bootstrap/dist/css/bootstrap.min.css';
import { motion } from 'framer-motion';

const LoginPage = () => {
    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');

    const handleSubmit = (event) => {
        event.preventDefault();

        if (!email || !password) {
            alert('Please fill in all fields.');
            return;
        }

        alert('Login successful!');
    };

    return (
        <div
            className="d-flex justify-content-center align-items-center min-vh-100"
            style={{
                background: 'linear-gradient(to bottom, #007f5f, #2b9348)',
                backgroundImage: "url('/cricket-field.jpg')",
                backgroundSize: 'cover',
                backgroundPosition: 'center'
            }}
        >
            <motion.div
                initial={{ opacity: 0, y: 50 }}
                animate={{ opacity: 1, y: 0 }}
                transition={{ duration: 0.8, ease: 'easeOut' }}
                className="card p-4 w-100"
                style={{
                    maxWidth: '400px',
                    borderRadius: '20px',
                    boxShadow: '0 6px 15px rgba(0, 0, 0, 0.2)',
                    backgroundColor: 'rgba(255, 255, 255, 0.9)'
                }}
            >
                <h2 className="text-center mb-4" style={{ fontWeight: 'bold', color: '#007f5f', fontSize: '1.8rem' }}>
                    Welcome Back to the Pitch
                </h2>
                <form onSubmit={handleSubmit}>
                    <motion.div
                        className="mb-3"
                        initial={{ opacity: 0 }}
                        animate={{ opacity: 1 }}
                        transition={{ delay: 0.2, duration: 0.5 }}
                    >
                        <label htmlFor="email" className="form-label">
                            Email Address
                        </label>
                        <input
                            type="email"
                            className="form-control"
                            id="email"
                            placeholder="Enter your email"
                            value={email}
                            onChange={(e) => setEmail(e.target.value)}
                            required
                            style={{ fontSize: '0.9rem' }}
                        />
                    </motion.div>
                    <motion.div
                        className="mb-3"
                        initial={{ opacity: 0 }}
                        animate={{ opacity: 1 }}
                        transition={{ delay: 0.4, duration: 0.5 }}
                    >
                        <label htmlFor="password" className="form-label">
                            Password
                        </label>
                        <input
                            type="password"
                            className="form-control"
                            id="password"
                            placeholder="Enter your password"
                            value={password}
                            onChange={(e) => setPassword(e.target.value)}
                            required
                            style={{ fontSize: '0.9rem' }}
                        />
                    </motion.div>
                    <motion.button
                        type="submit"
                        className="btn w-100 text-white"
                        style={{
                            backgroundColor: '#007f5f',
                            border: 'none',
                            fontWeight: 'bold',
                            fontSize: '1rem',
                            padding: '10px'
                        }}
                        onMouseOver={(e) => (e.target.style.backgroundColor = '#2b9348')}
                        onMouseOut={(e) => (e.target.style.backgroundColor = '#007f5f')}
                        whileHover={{ scale: 1.05 }}
                        whileTap={{ scale: 0.95 }}
                    >
                        Login
                    </motion.button>
                </form>
                <motion.div
                    className="text-center mt-3"
                    initial={{ opacity: 0 }}
                    animate={{ opacity: 1 }}
                    transition={{ delay: 0.6, duration: 0.5 }}
                >
                    <p className="small">
                        Don't have an account?{' '}
                        <a href="/register" className="text-decoration-none" style={{ color: '#2b9348', fontSize: '0.9rem' }}>
                            Sign up
                        </a>
                    </p>
                </motion.div>
            </motion.div>
        </div>
    );
};

export default LoginPage;
