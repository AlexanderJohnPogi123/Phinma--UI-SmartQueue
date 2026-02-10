export default function handler(req, res) {
  // Example: simulate user session
  // Replace this with your real auth logic (JWT, database, etc.)
  const userLoggedIn = req.query.user === "true"; // ?user=true to simulate login

  res.status(200).json({ loggedIn: userLoggedIn });
}
