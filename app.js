// api/session.js
export default function handler(req, res) {
  try {
    // Simulate a logged-in user
    // Pass ?user=true in query to simulate login
    const userLoggedIn = req.query.user === "true";

    res.status(200).json({ loggedIn: userLoggedIn });
  } catch (err) {
    console.error(err);
    res.status(500).json({ error: 'Internal Server Error' });
  }
}
